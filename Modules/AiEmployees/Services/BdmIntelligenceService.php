<?php

namespace Modules\AiEmployees\Services;

use Modules\AiEmployees\Models\AiEmployee;
use Prism\Prism\Facades\Prism;
use Modules\Mcp\Services\McpService;

class BdmIntelligenceService
{
    protected $mcpService;

    public function __construct(McpService $mcpService)
    {
        $this->mcpService = $mcpService;
    }

    /**
     * Dynamically call the LLM model using the settings, model, and system prompt 
     * of a specific database-defined AI Employee or sub-intelligence.
     */
    protected function callModel(AiEmployee $agent, string $prompt, array $tools = [])
    {
        $activeProvider = \Modules\AI\Models\AISetting::getSetting('active_provider') ?? 'openai';
        $apiKey = \Modules\AI\Models\AISetting::getSetting('api_key', $activeProvider);
        
        $dbModel = null;
        if ($agent->ai_model_id) {
            $dbModel = \Modules\AI\Models\AIModel::find($agent->ai_model_id);
        }
        
        if (!$dbModel) {
            $dbModel = \Modules\AI\Models\AIModel::active()->provider($activeProvider)->first();
        }
        
        $modelName = $dbModel ? $dbModel->name : match($activeProvider) {
            'gemini' => 'gemini-1.5-flash',
            'anthropic' => 'claude-3-5-sonnet-20240620',
            default => 'gpt-4o',
        };
        
        if ($apiKey) {
            config(["prism.providers.{$activeProvider}.api_key" => $apiKey]);
        }

        // Retrieve system prompt directly from database (strip Summernote HTML tags)
        $systemPrompt = strip_tags($agent->system_prompt);

        $attempts = 0;
        $maxAttempts = 4;
        $delay = 5;

        while ($attempts < $maxAttempts) {
            try {
                if ($attempts > 0) {
                    sleep($delay * $attempts + 2);
                }

                return Prism::text()
                    ->using($activeProvider, $modelName)
                    ->withPrompt("{$systemPrompt}\n\n{$prompt}")
                    ->withTools($tools)
                    ->withMaxSteps(5)
                    ->withMaxTokens(2000)
                    ->generate();

            } catch (\Exception $e) {
                $attempts++;
                if ($attempts >= $maxAttempts) {
                    throw $e;
                }

                $err = strtolower($e->getMessage());
                // If we hit a rate limit (RPM/TPM/Quota), sleep for 62 seconds to completely reset the window
                if (str_contains($err, 'rate limit') || str_contains($err, 'rate_limit') || str_contains($err, '429') || str_contains($err, 'limit')) {
                    sleep(62);
                } else {
                    sleep(5);
                }
            }
        }
    }

    /**
     * Run the multi-agent chain dynamically.
     * Loops through each active sub-agent of the manager, builds their toolkits, 
     * executes them in sequence, and records the output context.
     */
    public function runChain(AiEmployee $parent, string $task, callable $toolsCallback)
    {
        // 1. Fetch active sub-agents ordered by ID (so they execute in the order they are defined in database)
        $subAgents = $parent->subAgents()
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        if ($subAgents->isEmpty()) {
            // Fallback: If no sub-agents are active, run the parent manager alone
            $tools = $toolsCallback($parent);
            $response = $this->callModel($parent, $task, $tools);
            return [
                'success' => true,
                'result' => $response->text
            ];
        }

        $logs = [];
        $currentContext = "### Master Project Instruction / Mandate:\n\"{$task}\"\n\n";

        $logs[] = "### 🚀 Multi-Agent Task Orchestration Started\n" .
                  "Manager Agent: **{$parent->name}** is delegating the task to its specialized sub-intelligence squad.\n";

        foreach ($subAgents as $agent) {
            $logs[] = "--- Deployed Sub-Intelligence: **{$agent->name}** ({$agent->role}) ---";
            
            // Build the dynamic toolkit for this specific sub-agent
            $tools = $toolsCallback($agent);
            
            // Call the LLM using this sub-agent's system prompt and model
            $prompt = "Previous Conversation Context & Research History:\n" .
                      "--------------------------------------------------\n" .
                      $currentContext .
                      "--------------------------------------------------\n\n" .
                      "Current Step Instructions:\n" .
                      "Analyze the context above, perform your specialized role, and invoke any required tools:\n" .
                      "1. If you are discovering leads, call `bdm_search_web_for_leads` to search the web.\n" .
                      "2. If you are sourcing contact details or finalizing leads, you MUST invoke `bdm_create_lead` to save them directly to the database. Do not skip this.\n" .
                      "3. CRITICAL: In your text response, you MUST print the exact contact details (Email, Phone Number, Name, Company, Value) of every lead you find or save so the user has immediate visual confirmation in the UI logs. Do not omit the email or phone number.\n" .
                      "4. ⚠️ ANTI-FABRICATION RULE — STRICTLY ENFORCED: You are FORBIDDEN from inventing, guessing, or hallucinating any contact information. This includes:\n" .
                      "   - Do NOT generate fake emails like 'contact@companyname.com' or 'info@company.com' unless you have verified it exists.\n" .
                      "   - Do NOT generate sequential or placeholder phone numbers like '+2348000000001', '+234800000002', etc.\n" .
                      "   - If you do not have a verified, real email or phone number for a lead, leave those fields as null or omit them entirely.\n" .
                      "   - Only save contact info you have genuinely sourced from real web search results or known public data.\n" .
                      "   - It is far better to save a lead with no phone/email than to save a lead with a fabricated one.\n" .
                      "5. 🏢 COMPANY DETAILS REQUIREMENT: When invoking `bdm_create_lead`, you MUST write a highly detailed summary of the company in the `company_details` parameter. Analyze their business model, identify their digital presence weaknesses, manual workflows, and explain exactly how Rick Development Ltd can help them upgrade or automate. Be as specific and thorough as possible.";

            $response = $this->callModel($agent, $prompt, $tools);
            
            $agentOutput = trim($response->text);
            $logs[] = $agentOutput;
            
            // Log any tool calls made by this sub-agent
            if (!empty($response->toolCalls)) {
                $logs[] = "*(Actions taken: " . implode(', ', array_map(fn($c) => "Called `{$c->name}`", $response->toolCalls)) . ")*";
            }
            
            // Append this sub-agent's work to the context for the next sub-agent in the chain
            $currentContext .= "#### Agent {$agent->name} Output:\n{$agentOutput}\n\n";
        }

        $logs[] = "### 🏁 Multi-Agent Orchestration Completed Successfully";

        return [
            'success' => true,
            'result' => implode("\n\n", $logs)
        ];
    }
}
