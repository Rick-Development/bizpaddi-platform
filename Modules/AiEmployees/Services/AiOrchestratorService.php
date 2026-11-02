<?php

namespace Modules\AiEmployees\Services;

use Modules\AiEmployees\Models\AiEmployee;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Tool;
use Modules\Mcp\Services\McpService;

use Modules\AiEmployees\Services\BdmIntelligenceService;

class AiOrchestratorService
{
    protected $mcpService;
    protected $bdmService;

    public function __construct(McpService $mcpService, BdmIntelligenceService $bdmService)
    {
        $this->mcpService = $mcpService;
        $this->bdmService = $bdmService;
    }

    /**
     * Process a task using the specified AI Employee profile.
     */
    public function processTask(AiEmployee $employee, string $task, array $context = [])
    {
        // Route Business Development Manager (BDM) tasks to the dynamic multi-agent pipeline
        if ($employee->role === 'business_development') {
            return $this->bdmService->runChain($employee, $task, function($agent) {
                return $this->buildPrismTools($agent);
            });
        }

        try {
            $activeProvider = \Modules\AI\Models\AISetting::getSetting('active_provider') ?? 'openai';
            $apiKey = \Modules\AI\Models\AISetting::getSetting('api_key', $activeProvider);
            
            $dbModel = \Modules\AI\Models\AIModel::active()->provider($activeProvider)->first();
            $modelName = $dbModel ? $dbModel->name : match($activeProvider) {
                'gemini' => 'gemini-1.5-flash',
                'anthropic' => 'claude-3-5-sonnet-20240620',
                default => 'gpt-4o',
            };
            
            if ($apiKey) {
                config(["prism.providers.{$activeProvider}.api_key" => $apiKey]);
            }

            $contextStr = empty($context) ? '' : "\n\nContext:\n" . json_encode($context, JSON_PRETTY_PRINT);
            
            // Note: system_prompt contains HTML since we're using Summernote.
            // We strip tags to provide clean text to the LLM.
            $cleanSystemPrompt = strip_tags($employee->system_prompt);

            // Force the LLM to invoke tool calls to save data (like BDM leads) instead of only responding with text.
            $directive = "\n\nCRITICAL SYSTEM DIRECTIVES:\n" .
                         "1. If your task involves finding, creating, updating, or generating any business leads, customers, projects, or tasks, you MUST invoke the appropriate tool (e.g. `bdm_create_lead` to save a lead) to save them in the database. Do NOT just print them in your text response. You must execute the tool calls to persist them.\n" .
                         "2. 🚨 ZERO-FABRICATION MANDATE FOR CONTACT INFORMATION: You are STRICTLY FORBIDDEN from generating or guessing fake email addresses (e.g. @abiamfib.com, @deltammb.com) or sequential fake phone numbers (+234 704 567 8901, +234 803 123 4567). If genuine, verified email or phone numbers are unlisted or unknown, pass `null` or omit the `email` and `phone` parameters when calling `bdm_create_lead`. It is required to save leads with NULL email/phone when contact details are unverified.";

            $prompt = "{$cleanSystemPrompt}{$directive}\n\n" .
                      "Your specific task is:\n{$task}{$contextStr}\n\n" .
                      "Response (be concise and professional):";

            $tools = $this->buildPrismTools($employee);

            $attempts = 0;
            $maxAttempts = 3;
            $delay = 4;
            $response = null;
            
            while ($attempts < $maxAttempts) {
                try {
                    // Spread requests and backoff on retry to avoid provider rate limits
                    sleep($delay * $attempts + 2);
                    
                    $response = Prism::text()
                        ->using($activeProvider, $modelName)
                        ->withPrompt($prompt)
                        ->withTools($tools)
                        ->withMaxSteps(5)
                        ->withMaxTokens(1500)
                        ->generate();
                        
                    break;
                } catch (\Exception $e) {
                    $attempts++;
                    if ($attempts >= $maxAttempts) {
                        throw $e;
                    }
                    // If we hit a rate limit, sleep for 60 seconds to completely reset the OpenAI Requests Per Minute (RPM) window
                    $err = strtolower($e->getMessage());
                    if (str_contains($err, 'rate limit') || str_contains($err, 'rate_limit') || str_contains($err, '429')) {
                        sleep(61);
                    } else {
                        sleep(3);
                    }
                }
            }

            $responseText = $response->text;
            $toolSteps = [];
            
            // If the LLM decided to call tools, Prism will execute them and append the results 
            // to the message history, but we need to summarize it for the frontend or return the final text.
            // If we have tool calls in the response:
            if (!empty($response->toolCalls)) {
                foreach ($response->toolCalls as $call) {
                    $toolSteps[] = "Used Tool: {$call->name}";
                }
            }

            return [
                'success' => true,
                'result' => trim($responseText) . (!empty($toolSteps) ? "\n\n(Actions taken: " . implode(', ', $toolSteps) . ")" : ""),
                'model' => $modelName,
                'provider' => $activeProvider
            ];

        } catch (\Exception $e) {
            \Log::error('AiOrchestratorService task processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    protected function buildPrismTools(AiEmployee $employee)
    {
        $mcpTools = $this->mcpService->getRawTools();

        $tools = [];

        // 1. Shared tools (available to ALL employees)
        $tools[] = (new Tool())->as('search_company_knowledge')
            ->for($mcpTools['search_company_knowledge']['description'])
            ->withStringParameter('query', 'Search term or question')
            ->using(function(string $query) use ($employee, $mcpTools) {
                return $this->handleToolExecution('search_company_knowledge', [
                    'query' => $query
                ], $employee, $mcpTools);
            });

        // 2. Operations and General Manager tools
        if ($employee->role === 'operations_manager' || $employee->role === 'general_manager') {
            $tools[] = (new Tool())->as('ops_fetch_system_stats')
                ->for($mcpTools['ops_fetch_system_stats']['description'])
                ->using(function() use ($employee, $mcpTools) {
                    return $this->handleToolExecution('ops_fetch_system_stats', [], $employee, $mcpTools);
                });
        }

        // 3. Project Manager tools
        if ($employee->role === 'project_manager') {
            $tools[] = (new Tool())->as('pm_create_project')
                ->for($mcpTools['pm_create_project']['description'])
                ->withStringParameter('name', 'Project name')
                ->withStringParameter('description', 'A highly detailed, multi-paragraph project description outlining the scope, objectives, technical requirements, and deliverables. THIS MUST BE VERY LONG AND COMPREHENSIVE.')
                ->withStringParameter('status', 'Project status (default: active)')
                ->withStringParameter('start_date', 'Start date (Y-m-d)')
                ->withStringParameter('due_date', 'Due date (Y-m-d)')
                ->withNumberParameter('budget', 'Project budget')
                ->withNumberParameter('hourly_rate', 'Hourly rate')
                ->withNumberParameter('client_id', 'Client ID')
                ->using(function(
                    string $name, 
                    string $description = '',
                    string $status = 'active',
                    ?string $start_date = null,
                    ?string $due_date = null,
                    int|float $budget = 0,
                    int|float $hourly_rate = 0,
                    int|float $client_id = 0
                ) use ($employee, $mcpTools) {
                    return $this->handleToolExecution('pm_create_project', [
                        'name' => $name,
                        'description' => $description,
                        'status' => $status,
                        'start_date' => $start_date,
                        'due_date' => $due_date,
                        'budget' => $budget ?: null,
                        'hourly_rate' => $hourly_rate ?: null,
                        'client_id' => $client_id ?: null,
                    ], $employee, $mcpTools);
                });
                
            $tools[] = (new Tool())->as('pm_assign_task')
                ->for($mcpTools['pm_assign_task']['description'])
                ->withNumberParameter('project_id', 'Project ID')
                ->withStringParameter('title', 'Task title')
                ->withStringParameter('description', 'Task description')
                ->using(function(int|float $project_id, string $title, string $description) use ($employee, $mcpTools) {
                    return $this->handleToolExecution('pm_assign_task', [
                        'project_id' => (int)$project_id,
                        'title' => $title,
                        'description' => $description
                    ], $employee, $mcpTools);
                });
        }

        // 4. Social Media tools
        $isSmm = ($employee->role === 'social_media' || ($employee->parent && $employee->parent->role === 'social_media'));
        if ($isSmm) {
            $tools[] = (new Tool())->as('smm_create_campaign')
                ->for($mcpTools['smm_create_campaign']['description'])
                ->withStringParameter('name', 'Campaign name')
                ->withStringParameter('description', 'Campaign strategy description')
                ->withStringParameter('target_audience', 'Target audience')
                ->withNumberParameter('budget', 'Allocated budget')
                ->withStringParameter('start_date', 'Start Date')
                ->withStringParameter('end_date', 'End Date')
                ->using(function($name, $description, $target_audience, $budget, $start_date, $end_date) use ($employee, $mcpTools) {
                    return $this->handleToolExecution('smm_create_campaign', compact('name', 'description', 'target_audience', 'budget', 'start_date', 'end_date'), $employee, $mcpTools);
                });
                
            $tools[] = (new Tool())->as('smm_create_calendar')
                ->for($mcpTools['smm_create_calendar']['description'])
                ->withStringParameter('name', 'Calendar name')
                ->withStringParameter('theme', 'Calendar theme')
                ->withStringParameter('start_date', 'Start Date')
                ->withStringParameter('end_date', 'End Date')
                ->using(function($name, $theme, $start_date, $end_date) use ($employee, $mcpTools) {
                    return $this->handleToolExecution('smm_create_calendar', compact('name', 'theme', 'start_date', 'end_date'), $employee, $mcpTools);
                });
                
            $tools[] = (new Tool())->as('smm_analyze_trends')
                ->for($mcpTools['smm_analyze_trends']['description'])
                ->withStringParameter('platform', 'Platform')
                ->using(function($platform) use ($employee, $mcpTools) {
                    return $this->handleToolExecution('smm_analyze_trends', compact('platform'), $employee, $mcpTools);
                });
                
            $tools[] = (new Tool())->as('smm_competitor_analysis')
                ->for($mcpTools['smm_competitor_analysis']['description'])
                ->withStringParameter('competitor_name', 'Competitor Name')
                ->using(function($competitor_name) use ($employee, $mcpTools) {
                    return $this->handleToolExecution('smm_competitor_analysis', compact('competitor_name'), $employee, $mcpTools);
                });
                
            $tools[] = (new Tool())->as('smm_draft_post')
                ->for($mcpTools['smm_draft_post']['description'])
                ->withNumberParameter('campaign_id', 'Campaign ID')
                ->withNumberParameter('calendar_id', 'Calendar ID')
                ->withStringParameter('platform', 'Platform')
                ->withStringParameter('content', 'Content')
                ->withStringParameter('hashtags', 'JSON hashtags')
                ->withStringParameter('scheduled_for', 'Scheduled for')
                ->using(function($campaign_id, $calendar_id, $platform, $content, $hashtags, $scheduled_for) use ($employee, $mcpTools) {
                    return $this->handleToolExecution('smm_draft_post', compact('campaign_id', 'calendar_id', 'platform', 'content', 'hashtags', 'scheduled_for'), $employee, $mcpTools);
                });
                
            $tools[] = (new Tool())->as('smm_identify_lead')
                ->for($mcpTools['smm_identify_lead']['description'])
                ->withStringParameter('source_platform', 'Source Platform')
                ->withStringParameter('user_handle', 'User Handle')
                ->withStringParameter('context', 'Context')
                ->withStringParameter('intent_level', 'Intent Level')
                ->using(function($source_platform, $user_handle, $context, $intent_level) use ($employee, $mcpTools) {
                    return $this->handleToolExecution('smm_identify_lead', compact('source_platform', 'user_handle', 'context', 'intent_level'), $employee, $mcpTools);
                });
                
            $tools[] = (new Tool())->as('smm_generate_report')
                ->for($mcpTools['smm_generate_report']['description'])
                ->withStringParameter('title', 'Title')
                ->withStringParameter('period_start', 'Start')
                ->withStringParameter('period_end', 'End')
                ->withStringParameter('ai_analysis', 'Analysis')
                ->using(function($title, $period_start, $period_end, $ai_analysis) use ($employee, $mcpTools) {
                    return $this->handleToolExecution('smm_generate_report', compact('title', 'period_start', 'period_end', 'ai_analysis'), $employee, $mcpTools);
                });
        }

        // 5. Business Development tools (Available to Business Development AI and its sub-agents)
        $isBdm = ($employee->role === 'business_development' || ($employee->parent && $employee->parent->role === 'business_development'));
        if ($isBdm) {
            $tools[] = (new Tool())->as('bdm_create_lead')
                ->for($mcpTools['bdm_create_lead']['description'])
                ->withStringParameter('name', 'Name of the lead decision maker')
                ->withStringParameter('company', 'Name of the company/organization')
                ->withStringParameter('company_details', 'Detailed profile of the company, digital presence issues, and target upgrade scope')
                ->withStringParameter('email', 'Contact email address')
                ->withStringParameter('phone', 'Contact phone number')
                ->withStringParameter('notes', 'Detailed background notes and lead context')
                ->withNumberParameter('value', 'Expected deal value in USD')
                ->using(function($name, $company, $company_details = null, $email = null, $phone = null, $notes = '', $value = null) use ($employee, $mcpTools) {
                    return $this->handleToolExecution('bdm_create_lead', [
                        'name' => $name,
                        'company' => $company,
                        'company_details' => $company_details,
                        'email' => $email,
                        'phone' => $phone,
                        'notes' => $notes,
                        'value' => $value,
                        'ai_employee_id' => $employee->id
                    ], $employee, $mcpTools);
                });

            $tools[] = (new Tool())->as('bdm_list_leads')
                ->for($mcpTools['bdm_list_leads']['description'])
                ->withStringParameter('status', 'Filter status')
                ->using(function($status = null) use ($employee, $mcpTools) {
                    return $this->handleToolExecution('bdm_list_leads', compact('status'), $employee, $mcpTools);
                });

            $tools[] = (new Tool())->as('bdm_update_lead_status')
                ->for($mcpTools['bdm_update_lead_status']['description'])
                ->withNumberParameter('lead_id', 'Lead ID')
                ->withStringParameter('status', 'New status')
                ->withStringParameter('notes', 'Status notes')
                ->using(function($lead_id, $status, $notes = '') use ($employee, $mcpTools) {
                    return $this->handleToolExecution('bdm_update_lead_status', [
                        'lead_id' => (int)$lead_id,
                        'status' => $status,
                        'notes' => $notes
                    ], $employee, $mcpTools);
                });

            $tools[] = (new Tool())->as('bdm_search_web_for_leads')
                ->for($mcpTools['bdm_search_web_for_leads']['description'])
                ->withStringParameter('query', 'The search query to find leads')
                ->using(function($query) use ($employee, $mcpTools) {
                    return $this->handleToolExecution('bdm_search_web_for_leads', compact('query'), $employee, $mcpTools);
                });
        }

        // Inject consult_sub_agent tool if this employee has active sub-agents
        $activeSubAgents = $employee->subAgents()->where('is_active', true)->get();
        if ($activeSubAgents->isNotEmpty()) {
            $subAgentOptions = $activeSubAgents->map(function($sub) {
                return "ID {$sub->id}: {$sub->name} ({$sub->role})";
            })->implode("\n");

            $tools[] = (new Tool())->as('consult_sub_agent')
                ->for("Consult a specialized sub-agent for a task. Available agents:\n{$subAgentOptions}")
                ->withStringParameter('agent_id', 'The ID (number) or role key of the sub-agent to consult (e.g. "76" or "copywriting_intelligence")')
                ->withStringParameter('task', 'The specific prompt/task for the sub-agent')
                ->using(function(string $agent_id, string $task) use ($employee, $activeSubAgents) {
                    $subAgent = is_numeric($agent_id)
                        ? $activeSubAgents->firstWhere('id', (int)$agent_id)
                        : $activeSubAgents->firstWhere('role', $agent_id);
                        
                    if (!$subAgent) {
                        return "Error: Sub-agent with ID or role '{$agent_id}' not found, not active, or not assigned to you.";
                    }
                    
                    // Recursive call to orchestrator for the sub-agent
                    // Note: We create a new instance of the orchestrator to avoid state issues (though Prism is stateless here)
                    $subResult = $this->processTask($subAgent, $task, [
                        'delegated_by' => $employee->name,
                        'original_context' => "You are acting as a sub-agent to {$employee->name}."
                    ]);
                    
                    if ($subResult['success']) {
                        return "Sub-agent {$subAgent->name} responded:\n" . $subResult['result'];
                    } else {
                        return "Sub-agent {$subAgent->name} failed to respond: " . ($subResult['error'] ?? 'Unknown error');
                    }
                });
        }

        return $tools;
    }

    protected function handleToolExecution($toolName, $args, $employee, $mcpTools)
    {
        $requiresApproval = $mcpTools[$toolName]['requires_approval'] ?? false;
        
        if ($requiresApproval) {
            \Modules\AiEmployees\Models\AiPendingAction::create([
                'ai_employee_id' => $employee->id,
                'tool_name' => $toolName,
                'tool_arguments' => $args,
                'status' => 'pending'
            ]);
            
            // Send email notification
            \Illuminate\Support\Facades\Mail::raw(
                "AI Employee '{$employee->name}' wants to execute '{$toolName}'. Please approve this in the Command Center.", 
                function ($message) {
                    $message->to(config('mail.from.address', 'info@bizpaddi.com'))
                            ->subject('Action Required: AI Action Pending Approval');
                }
            );
            
            return "Action queued for human approval. Inform the user that the action requires their approval before proceeding.";
        }
        
        $result = $this->mcpService->executeTool($toolName, $args);
        return $result['content'][0]['text'] ?? json_encode($result);
    }
}
