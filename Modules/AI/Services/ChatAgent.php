<?php

namespace Modules\AI\Services;

use Modules\AI\Models\ChatConversation;
use Modules\AI\Models\ChatMessage;
use Modules\Mcp\Services\McpService;
use Prism\Prism\Facades\Prism;
use Illuminate\Support\Str;

/**
 * ChatAgent - AI-powered customer support chatbot with MCP tool integration
 * 
 * This service handles:
 * - Live chat conversations
 * - Customer support automation
 * - MCP tool execution (blog creation, task assignment, etc.)
 * - Lead qualification
 * - Sentiment analysis
 * - Conversation summarization
 */
class ChatAgent
{
    protected $mcpService;

    public function __construct(McpService $mcpService)
    {
        $this->mcpService = $mcpService;
    }
    /**
     * Start a new chat conversation.
     *
     * @param array $params
     * @return ChatConversation
     */
    public function startConversation(array $params = []): ChatConversation
    {
        return ChatConversation::create([
            'user_id' => $params['user_id'] ?? (auth()->check() ? auth()->id() : null),
            'session_id' => $params['session_id'] ?? Str::uuid(),
            'visitor_name' => $params['visitor_name'] ?? null,
            'visitor_email' => $params['visitor_email'] ?? null,
            'status' => 'active',
        ]);
    }

    /**
     * Send a message and get AI response.
     *
     * @param ChatConversation $conversation
     * @param string $userMessage
     * @return ChatMessage
     */
    public function sendMessage(ChatConversation $conversation, string $userMessage): ChatMessage
    {
        // Save user message
        $userMsg = $conversation->messages()->create([
            'role' => 'user',
            'message' => $userMessage,
        ]);

        // Get conversation history for context
        $history = $this->getConversationHistory($conversation);

        // Generate AI response
        $aiResponse = $this->generateResponse($userMessage, $history, $conversation);

        // Save AI message
        $aiMsg = $conversation->messages()->create([
            'role' => 'assistant',
            'message' => $aiResponse['message'],
            'metadata' => [
                'model' => $aiResponse['model'],
                'tokens' => $aiResponse['tokens'] ?? null,
                'sentiment' => $aiResponse['sentiment'] ?? null,
            ],
        ]);

        // Update conversation
        $conversation->update([
            'last_message_at' => now(),
            'sentiment' => $aiResponse['sentiment'] ?? $conversation->sentiment,
        ]);

        return $aiMsg;
    }

    /**
     * Generate AI response.
     *
     * @param string $userMessage
     * @param array $history
     * @param ChatConversation $conversation
     * @return array
     */
    protected function generateResponse(string $userMessage, array $history, ChatConversation $conversation): array
    {
        try {
            // Get Configuration
            $provider = \Modules\AI\Models\AISetting::getSetting('active_provider') ?? 'openai';
            $apiKey = \Modules\AI\Models\AISetting::getSetting('api_key', $provider);
            $systemPrompt = $this->buildSystemPromptWithTools($conversation);
            $conversationContext = $this->formatHistory($history);

            // Determine Model based on provider
            $dbModel = \Modules\AI\Models\AIModel::active()->provider($provider)->first();
            $model = $dbModel ? $dbModel->name : match($provider) {
                'gemini' => 'gemini-1.5-flash',
                'anthropic' => 'claude-3-5-sonnet-20240620',
                default => 'gpt-4o',
            };

            // Inject API Key
            if ($apiKey) {
                config(["prism.providers.{$provider}.api_key" => $apiKey]);
            }

            // Check if user wants to use MCP tools
            $toolIntent = $this->detectToolIntent($userMessage);
            
            if ($toolIntent) {
                return $this->handleToolExecution($toolIntent, $userMessage, $provider, $model);
            }

            // Regular chat response
            $prism = Prism::text()
                ->using($provider, $model)
                ->withPrompt("{$systemPrompt}\n\nConversation history:\n{$conversationContext}\n\nUser: {$userMessage}\n\nAssistant:")
                ->withMaxTokens(500)
                ->withProviderOptions(['temperature' => 0.7]);

            $response = $prism->asText();
            $sentiment = $this->analyzeSentiment($userMessage);

            return [
                'message' => trim($response->text),
                'model' => $model,
                'tokens' => $response->usage->total ?? null,
                'sentiment' => $sentiment,
            ];
        } catch (\Exception $e) {
            \Log::error('AI chat response failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'message' => "I apologize, but I'm having trouble processing your request right now. A human agent will assist you shortly.",
                'model' => 'fallback',
                'sentiment' => 'neutral',
            ];
        }
    }

    /**
     * Build system prompt for the chatbot.
     *
     * @param ChatConversation $conversation
     * @return string
     */
    protected function buildSystemPrompt(ChatConversation $conversation): string
    {
        $customPrompt = \Modules\AI\Models\AISetting::getSetting('system_prompt');
        if ($customPrompt) {
            return $customPrompt;
        }

        $companyName = config('app.name', 'Rick Development LTD');
        
        return "You are a helpful customer support assistant for {$companyName}. " .
               "You provide professional, friendly, and accurate information about our services, products, and solutions. " .
               "If you don't know something, be honest and offer to connect the customer with a human agent. " .
               "Keep responses concise and helpful. " .
               "Focus on solving the customer's problem efficiently.";
    }

    /**
     * Get conversation history.
     *
     * @param ChatConversation $conversation
     * @param int $limit
     * @return array
     */
    protected function getConversationHistory(ChatConversation $conversation, int $limit = 10): array
    {
        return $conversation->messages()
            ->latest()
            ->limit($limit)
            ->get()
            ->reverse()
            ->map(fn($msg) => [
                'role' => $msg->role,
                'message' => $msg->message,
            ])
            ->toArray();
    }

    /**
     * Format conversation history for prompt.
     *
     * @param array $history
     * @return string
     */
    protected function formatHistory(array $history): string
    {
        if (empty($history)) {
            return '(No previous messages)';
        }

        return collect($history)
            ->map(fn($msg) => ucfirst($msg['role']) . ': ' . $msg['message'])
            ->implode("\n");
    }

    /**
     * Analyze sentiment of user message.
     *
     * @param string $message
     * @return string
     */
    protected function analyzeSentiment(string $message): string
    {
        try {
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Analyze the sentiment of this customer message and respond with only one word: positive, neutral, or negative.\n\nMessage: {$message}")
                ->withMaxTokens(10)
                ->generate();

            $sentiment = strtolower(trim($response->text));
            return in_array($sentiment, ['positive', 'neutral', 'negative']) ? $sentiment : 'neutral';
        } catch (\Exception $e) {
            return 'neutral';
        }
    }

    /**
     * Summarize conversation.
     *
     * @param ChatConversation $conversation
     * @return string
     */
    public function summarizeConversation(ChatConversation $conversation): string
    {
        try {
            $history = $this->getConversationHistory($conversation, 50);
            $conversationText = $this->formatHistory($history);

            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Summarize this customer support conversation in 2-3 sentences, highlighting the main issue and resolution:\n\n{$conversationText}")
                ->withMaxTokens(150)
                ->generate();

            $summary = trim($response->text);
            $conversation->update(['summary' => $summary]);

            return $summary;
        } catch (\Exception $e) {
            \Log::error('Conversation summarization failed: ' . $e->getMessage());
            return 'Summary unavailable';
        }
    }

    /**
     * Close conversation.
     *
     * @param ChatConversation $conversation
     * @param string $status
     * @return ChatConversation
     */
    public function closeConversation(ChatConversation $conversation, string $status = 'resolved'): ChatConversation
    {
        // Generate summary before closing
        if (empty($conversation->summary)) {
            $this->summarizeConversation($conversation);
        }

        $conversation->update(['status' => $status]);
        return $conversation->fresh();
    }

    /**
     * Qualify lead based on conversation.
     *
     * @param ChatConversation $conversation
     * @return array
     */
    public function qualifyLead(ChatConversation $conversation): array
    {
        try {
            $history = $this->getConversationHistory($conversation, 50);
            $conversationText = $this->formatHistory($history);

            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Analyze this conversation and provide a lead qualification score (1-10) and brief reason:\n\n{$conversationText}\n\nRespond in format: Score: X, Reason: Y")
                ->withMaxTokens(100)
                ->generate();

            // Parse response
            $text = $response->text;
            preg_match('/Score:\s*(\d+)/', $text, $scoreMatch);
            preg_match('/Reason:\s*(.+)/', $text, $reasonMatch);

            return [
                'score' => (int)($scoreMatch[1] ?? 5),
                'reason' => trim($reasonMatch[1] ?? 'Unable to determine'),
                'sentiment' => $conversation->sentiment,
            ];
        } catch (\Exception $e) {
            \Log::error('Lead qualification failed: ' . $e->getMessage());
            return [
                'score' => 5,
                'reason' => 'Analysis unavailable',
                'sentiment' => $conversation->sentiment ?? 'neutral',
            ];
        }
    }

    /**
     * Build system prompt with MCP tool capabilities.
     */
    protected function buildSystemPromptWithTools(ChatConversation $conversation): string
    {
        $basePrompt = $this->buildSystemPrompt($conversation);
        
        return $basePrompt . "\n\n" .
               "You also have access to powerful automation tools:\n" .
               "- Create blog posts (just ask me to write a blog about any topic)\n" .
               "- Assign tasks to developers (I'll find the least busy developer)\n" .
               "- Create invoices and quotes\n" .
               "When users ask you to create content, you can generate it automatically.";
    }

    /**
     * Detect if user wants to use an MCP tool.
     */
    protected function detectToolIntent(string $message): ?array
    {
        $message = strtolower($message);
        
        // Blog creation patterns
        if (preg_match('/(create|write|generate|make)\s+(a\s+)?(blog|post|article)/i', $message)) {
            return ['tool' => 'blog_create_post', 'type' => 'blog'];
        }
        
        // Task assignment patterns
        if (preg_match('/(assign|create|add)\s+(a\s+)?(task|ticket)/i', $message)) {
            return ['tool' => 'pm_assign_task', 'type' => 'task'];
        }
        
        // Invoice patterns
        if (preg_match('/(create|generate|make)\s+(an\s+)?invoice/i', $message)) {
            return ['tool' => 'finance_create_invoice', 'type' => 'invoice'];
        }
        
        // Quote patterns
        if (preg_match('/(create|generate|make)\s+(a\s+)?quote/i', $message)) {
            return ['tool' => 'finance_create_quote', 'type' => 'quote'];
        }
        
        return null;
    }

    /**
     * Handle MCP tool execution with AI-generated content.
     */
    protected function handleToolExecution(array $toolIntent, string $userMessage, string $provider, string $model): array
    {
        try {
            $tool = $toolIntent['tool'];
            $type = $toolIntent['type'];
            
            // Generate appropriate content based on tool type
            $params = $this->generateToolParameters($type, $userMessage, $provider, $model);
            
            // Execute the MCP tool
            $result = $this->mcpService->executeTool($tool, $params);
            
            // Format success response
            $responseText = $result['content'][0]['text'] ?? 'Action completed successfully!';
            
            return [
                'message' => "✅ Done! {$responseText}",
                'model' => $model,
                'sentiment' => 'positive',
                'tool_used' => $tool,
            ];
        } catch (\Exception $e) {
            \Log::error('MCP tool execution failed', [
                'tool' => $toolIntent['tool'],
                'error' => $e->getMessage()
            ]);
            
            return [
                'message' => "I tried to {$toolIntent['type']} for you, but encountered an error: {$e->getMessage()}",
                'model' => $model,
                'sentiment' => 'neutral',
            ];
        }
    }

    /**
     * Generate parameters for MCP tools using AI.
     */
    protected function generateToolParameters(string $type, string $userMessage, string $provider, string $model): array
    {
        switch ($type) {
            case 'blog':
                return $this->generateBlogParameters($userMessage, $provider, $model);
            case 'task':
                return $this->generateTaskParameters($userMessage, $provider, $model);
            case 'invoice':
            case 'quote':
                return $this->generateFinanceParameters($type, $userMessage, $provider, $model);
            default:
                throw new \Exception("Unknown tool type: {$type}");
        }
    }

    /**
     * Generate blog post parameters using AI.
     */
    protected function generateBlogParameters(string $userMessage, string $provider, string $model): array
    {
        // Get fresh provider settings from database
        $activeProvider = \Modules\AI\Models\AISetting::getSetting('active_provider') ?? 'openai';
        $apiKey = \Modules\AI\Models\AISetting::getSetting('api_key', $activeProvider);
        $dbModel = \Modules\AI\Models\AIModel::active()->provider($activeProvider)->first();
        $modelName = $dbModel ? $dbModel->name : ($activeProvider === 'openai' ? 'gpt-4o' : 'gemini-1.5-flash');
        if ($apiKey) {
            config(["prism.providers.{$activeProvider}.api_key" => $apiKey]);
        }

        $prompt = "Based on this request: '{$userMessage}'\n\n" .
                  "Generate a comprehensive blog post with:\n" .
                  "1. A catchy title (max 100 chars)\n" .
                  "2. A compelling SEO meta title (max 60 chars)\n" .
                  "3. A concise excerpt (max 160 chars)\n" .
                  "4. Well-structured content in Markdown format (at least 500 words)\n" .
                  "5. A prompt to generate a featured image for this post\n" .
                  "6. Relevant tags (comma-separated)\n\n" .
                  "Format your response EXACTLY as:\n" .
                  "TITLE: [title]\n" .
                  "META_TITLE: [meta title]\n" .
                  "EXCERPT: [excerpt]\n" .
                  "IMAGE_PROMPT: [image description]\n" .
                  "TAGS: [tags]\n" .
                  "CONTENT:\n[content in markdown]";

        $response = Prism::text()
            ->using($activeProvider, $modelName)
            ->withPrompt($prompt)
            ->withMaxTokens(2500)
            ->asText();

        // Parse AI response
        $text = $response->text;
        preg_match('/TITLE:\s*(.+)/i', $text, $titleMatch);
        preg_match('/META_TITLE:\s*(.+)/i', $text, $metaTitleMatch);
        preg_match('/EXCERPT:\s*(.+)/i', $text, $excerptMatch);
        preg_match('/IMAGE_PROMPT:\s*(.+)/i', $text, $imagePromptMatch);
        preg_match('/TAGS:\s*(.+)/i', $text, $tagsMatch);
        preg_match('/CONTENT:\s*(.+)/is', $text, $contentMatch);

        $markdownContent = trim($contentMatch[1] ?? $text);
        
        // Convert Markdown to HTML
        $htmlContent = $markdownContent; // \Illuminate\Support\Str::markdown($markdownContent);
        
        // Calculate reading time
        $wordCount = str_word_count(strip_tags($htmlContent));
        $readingTime = ceil($wordCount / 200); // Avg 200 wpm

        return [
            'title' => trim($titleMatch[1] ?? 'AI Generated Post'),
            'slug' => \Illuminate\Support\Str::slug(trim($titleMatch[1] ?? 'ai-generated-post')),
            'excerpt' => trim($excerptMatch[1] ?? ''),
            'content' => $htmlContent,
            'category_id' => 1, // Default category
            'author_id' => auth()->id() ?? 1,
            'meta_title' => trim($metaTitleMatch[1] ?? trim($titleMatch[1] ?? '')),
            'meta_description' => trim($excerptMatch[1] ?? ''),
            'meta_keywords' => trim($tagsMatch[1] ?? ''),
            'ai_generated' => true,
            'ai_model' => $modelName,
            'ai_generated_at' => now(),
            'ai_metadata' => json_encode([
                'prompt' => $userMessage,
                'provider' => $activeProvider,
                'tokens' => $wordCount // Approximation
            ]),
            'featured_image_prompt' => trim($imagePromptMatch[1] ?? ''),
            'status' => 'published',
            'published_at' => now(),
            'scheduled_at' => null,
            'views_count' => 0,
            'reading_time' => $readingTime,
            'featured_image' => null, // Placeholder
        ];
    }

    /**
     * Generate task parameters using AI.
     */
    protected function generateTaskParameters(string $userMessage, string $provider, string $model): array
    {
        // Get fresh provider settings from database
        $activeProvider = \Modules\AI\Models\AISetting::getSetting('active_provider') ?? 'openai';
        $apiKey = \Modules\AI\Models\AISetting::getSetting('api_key', $activeProvider);
        
        // Get active model for this provider
        $dbModel = \Modules\AI\Models\AIModel::active()->provider($activeProvider)->first();
        $modelName = $dbModel ? $dbModel->name : match($activeProvider) {
            'gemini' => 'gemini-1.5-flash',
            'anthropic' => 'claude-3-5-sonnet-20240620',
            default => 'gpt-4o',
        };
        
        // Set API key
        if ($apiKey) {
            config(["prism.providers.{$activeProvider}.api_key" => $apiKey]);
        }

        $prompt = "Based on this request: '{$userMessage}'\n\n" .
                  "Create a task with:\n" .
                  "1. Clear title\n" .
                  "2. Detailed description\n" .
                  "3. Estimated hours (number only)\n\n" .
                  "Format as:\n" .
                  "TITLE: [title]\n" .
                  "DESCRIPTION: [description]\n" .
                  "HOURS: [number]";

        $response = Prism::text()
            ->using($activeProvider, $modelName)
            ->withPrompt($prompt)
            ->withMaxTokens(500)
            ->asText();

        $text = $response->text;
        preg_match('/TITLE:\s*(.+)/i', $text, $titleMatch);
        preg_match('/DESCRIPTION:\s*(.+)/is', $text, $descMatch);
        preg_match('/HOURS:\s*(\d+)/i', $text, $hoursMatch);

        return [
            'project_id' => 1, // Default project
            'title' => trim($titleMatch[1] ?? 'AI Generated Task'),
            'description' => trim($descMatch[1] ?? $userMessage),
            'estimated_hours' => (int)($hoursMatch[1] ?? 4),
        ];
    }

    /**
     * Generate finance parameters (Quote/Invoice) using AI.
     */
    protected function generateFinanceParameters(string $type, string $userMessage, string $provider, string $model): array
    {
        // Get fresh provider settings
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

        $prompt = "Based on this request: '{$userMessage}'\n\n" .
                  "Generate a {$type} with:\n" .
                  "1. A list of items (title, description, quantity, unit_price)\n" .
                  "2. Use reasonable market rates if prices aren't specified\n" .
                  "3. Detect currency (default to 'NGN' unless fully specified otherwise)\n\n" .
                  "Format your response EXACTLY as JSON:\n" .
                  "{\n" .
                  "  \"currency_code\": \"NGN\",\n" .
                  "  \"items\": [\n" .
                  "    {\"title\": \"Item Title\", \"description\": \"Detailed description\", \"quantity\": 1, \"unit_price\": 100.00}\n" .
                  "  ]\n" .
                  "}";

        $response = Prism::text()
            ->using($activeProvider, $modelName)
            ->withPrompt($prompt)
            ->withMaxTokens(1000)
            ->asText();

        // Parse JSON from response
        $text = $response->text;
        if (preg_match('/```json\s*(.*?)\s*```/s', $text, $matches)) {
            $jsonStr = $matches[1];
        } else {
            $jsonStr = $text;
        }

        $data = json_decode($jsonStr, true);
        $items = $data['items'] ?? [];
        $currencyCode = $data['currency_code'] ?? 'NGN';

        // Fallback if parsing fails
        if (empty($items)) {
            $items = [
                ['title' => 'Service', 'description' => 'Service requested: ' . substr($userMessage, 0, 50), 'quantity' => 1, 'unit_price' => 100]
            ];
        }

        // Find currency ID
        $currencyId = \Modules\Accounting\Models\Currency::where('code', $currencyCode)->value('id') 
                      ?? \Modules\Accounting\Models\Currency::where('code', 'NGN')->value('id') 
                      ?? 1;

        return [
            'client_id' => 1, // Default to first client
            'currency_id' => $currencyId,
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(14)->toDateString(),
            'status' => 'draft',
            'items' => $items
        ];
    }
}

