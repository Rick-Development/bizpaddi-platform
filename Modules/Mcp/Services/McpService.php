<?php

namespace Modules\Mcp\Services;

use Modules\Blog\Models\Post;
use Modules\Blog\Models\Category;
use Illuminate\Support\Str;
use Modules\ProjectManagement\Models\PMProject;
use Modules\ProjectManagement\Models\Task;
use App\Models\User;
use Modules\TeamPortal\Models\WorkLog;
use Modules\Accounting\Models\Invoice;
use Modules\Accounting\Models\Quote;

class McpService
{
    protected $tools = [];

    public function __construct()
    {
        $this->registerTools();
    }

    protected function registerTools()
    {
        $this->tools = [
            'blog_create_post' => [
                'description' => 'Create a new blog post',
                'parameters' => [
                    'title' => 'string: The title of the post',
                    'content' => 'string: The full HTML content of the post',
                    'category_id' => 'integer: (Optional) ID of the blog category',
                    'tags' => 'string: (Optional) Comma separated tags',
                    'published' => 'boolean: (Optional) Whether to publish immediately, default true'
                ],
                'handler' => fn($args) => $this->createBlogPost($args)
            ],
            'pm_create_project' => [
                'description' => 'Create a new project',
                'parameters' => [
                    'name' => 'string: Project name',
                    'description' => 'string: Project description',
                    'client_id' => 'integer: (Optional) Client ID',
                    'status' => 'string: (Optional) Status (default: active)'
                ],
                'handler' => fn($args) => $this->createProject($args)
            ],
            'pm_assign_task' => [
                'description' => 'Create a task and assign it to the developer with the lowest workload',
                'parameters' => [
                    'project_id' => 'integer: Project ID',
                    'title' => 'string: Task title',
                    'description' => 'string: Task description',
                    'priority' => 'string: (Optional) high, medium, low',
                    'due_date' => 'string: (Optional) YYYY-MM-DD'
                ],
                'handler' => fn($args) => $this->smartAssignTask($args)
            ],
            'finance_create_invoice' => [
                'description' => 'Create a new invoice for a client',
                'parameters' => [
                    'client_id' => 'integer: Client ID',
                    'due_date' => 'string: (Optional) Due date YYYY-MM-DD',
                    'items' => 'string: JSON string of items e.g. [{"description": "Service A", "amount": 100}]'
                ],
                'handler' => fn($args) => $this->createInvoice($args)
            ],
            'finance_create_quote' => [
                'description' => 'Create a new quote for a client',
                'parameters' => [
                    'client_id' => 'integer: Client ID',
                    'valid_until' => 'string: (Optional) Expiration date YYYY-MM-DD',
                    'items' => 'string: JSON string of items e.g. [{"description": "Service A", "amount": 100}]'
                ],
                'handler' => fn($args) => $this->createQuote($args)
            ],
            // Future tools will be added here
        ];
    }

    public function getTools()
    {
        return array_map(fn($tool, $name) => [
            'name' => $name,
            'description' => $tool['description'],
            'inputSchema' => [
                'type' => 'object',
                'properties' => $this->parseParameters($tool['parameters']),
                'required' => array_keys($tool['parameters']) // simplifying for now
            ]
        ], $this->tools, array_keys($this->tools));
    }

    public function executeTool($name, $arguments)
    {
        if (!isset($this->tools[$name])) {
            throw new \Exception("Tool not found: $name");
        }

        return $this->tools[$name]['handler']($arguments);
    }

    protected function createBlogPost($args)
    {
        $title = $args['title'];
        $content = $args['content'];
        $categoryId = $args['category_id'] ?? Category::first()->id ?? null; // Fallback to first category
        $tags = $args['tags'] ?? '';
        $isPublished = $args['published'] ?? true;

        $post = Post::create([
            'title' => $args['title'],
            'slug' => $args['slug'] ?? Str::slug($args['title']),
            'excerpt' => $args['excerpt'] ?? Str::limit(strip_tags($args['content']), 150),
            'content' => $args['content'],
            'category_id' => $args['category_id'] ?? 1,
            'author_id' => $args['author_id'] ?? auth()->id() ?? 1,
            'meta_title' => $args['meta_title'] ?? $args['title'],
            'meta_description' => $args['meta_description'] ?? null,
            'meta_keywords' => $args['meta_keywords'] ?? null,
            'ai_generated' => $args['ai_generated'] ?? false,
            'ai_model' => $args['ai_model'] ?? null,
            'ai_generated_at' => $args['ai_generated_at'] ?? null,
            'ai_metadata' => $args['ai_metadata'] ?? null,
            'featured_image_prompt' => $args['featured_image_prompt'] ?? null,
            'featured_image' => $args['featured_image'] ?? null,
            'status' => $args['status'] ?? 'draft',
            'published_at' => $args['published_at'] ?? null,
            'scheduled_at' => $args['scheduled_at'] ?? null,
            'views_count' => 0,
            'reading_time' => $args['reading_time'] ?? ceil(str_word_count(strip_tags($args['content'])) / 200),
        ]);

        return [
            'content' => [
                [
                    'type' => 'text',
                    'text' => "Successfully created blog post: {$post->title} (ID: {$post->id}) View at: " . route('blog.show', $post->slug)
                ]
            ]
        ];
    }

    protected function createProject($args)
    {
        $project = PMProject::create([
            'name' => $args['name'],
            'description' => $args['description'] ?? '',
            'client_id' => $args['client_id'] ?? null,
            'status' => $args['status'] ?? 'active',
            'manager_id' => auth()->id() ?? 1
        ]);

        return ['content' => [['type' => 'text', 'text' => "Project created: {$project->name} (ID: {$project->id})"]]];
    }

    protected function smartAssignTask($args)
    {
        // 1. Find the best dev
        // Strategy: Lowest total duration in the last 30 days
        
        $users = User::all(); // In reality, filter by role/department 'Developer'
        
        $bestDev = null;
        $lowestLoad = -1;

        foreach ($users as $user) {
            $load = WorkLog::where('user_id', $user->id)
                ->where('start_time', '>=', now()->subDays(30))
                ->sum('duration_minutes');
            
            // Heuristic adjustment: +100 mins for every active task (assumed)
            $activeTasks = Task::where('assigned_to', $user->id)->where('status', '!=', 'completed')->count();
            $effectiveLoad = $load + ($activeTasks * 100);

            if ($lowestLoad === -1 || $effectiveLoad < $lowestLoad) {
                $lowestLoad = $effectiveLoad;
                $bestDev = $user;
            }
        }

        if (!$bestDev) {
            return ['content' => [['type' => 'text', 'text' => "No available developers found."]]];
        }

        // 2. Create and assign task
        $task = Task::create([
            'project_id' => $args['project_id'],
            'title' => $args['title'],
            'description' => $args['description'] ?? '',
            'priority' => $args['priority'] ?? 'medium',
            'status' => 'todo',
            'assigned_to' => $bestDev->id,
            'assigned_by' => auth()->id() ?? 1,
            'due_date' => $args['due_date'] ?? null,
        ]);

        return ['content' => [['type' => 'text', 'text' => "Task '{$task->title}' assigned to {$bestDev->name} (Current Load: " . round($lowestLoad/60, 1) . " hrs)"]]];
    }

    protected function createInvoice($args)
    {
        $client = \Modules\Clients\Models\Client::find($args['client_id']);

        $invoice = Invoice::create([
            'client_id' => $args['client_id'],
            'client_name' => $client ? $client->name : 'Unknown Client',
            'client_email' => $client ? $client->email : null,
            'status' => 'draft',
            'issue_date' => now(),
            'due_date' => $args['due_date'] ?? now()->addDays(14),
            'currency_id' => $args['currency_id'] ?? 1, // Default to 1 if not provided
            'sub_total' => 0,
            'total' => 0,
            'created_by' => auth()->id() ?? 1,
            'invoice_number' => Invoice::generateInvoiceNumber(),
        ]);
        
        $total = 0;
        $items = is_string($args['items']) ? json_decode($args['items'], true) : $args['items'] ?? [];
        
        foreach ($items as $item) {
            \Modules\Accounting\Models\InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'title' => $item['title'] ?? 'Service',
                'description' => $item['description'] ?? '',
                'quantity' => $item['quantity'] ?? 1,
                'unit_price' => $item['unit_price'] ?? 0,
                'total' => ($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0),
            ]);
            
            $total += ($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0);
        }
        
        $invoice->update(['subtotal' => $total, 'total_amount' => $total]);

        return ['content' => [['type' => 'text', 'text' => "Invoice created: #{$invoice->invoice_number} (ID: {$invoice->id}) for Client {$invoice->client_name}"]]];
    }

    protected function createQuote($args)
    {
        $client = \Modules\Clients\Models\Client::find($args['client_id']);
        
        $quote = Quote::create([
            'client_id' => $args['client_id'],
            'client_name' => $client ? $client->name : 'Unknown Client',
            'client_email' => $client ? $client->email : null,
            'status' => 'draft',
            'issue_date' => now(),
            'valid_until' => $args['valid_until'] ?? now()->addDays(30),
            'currency_id' => $args['currency_id'] ?? 1, // Default
            'sub_total' => 0,
            'total' => 0,
            'created_by' => auth()->id() ?? 1,
            'quote_number' => Quote::generateQuoteNumber(),
        ]);

        $total = 0;
        $items = is_string($args['items']) ? json_decode($args['items'], true) : $args['items'] ?? [];
        
        foreach ($items as $item) {
            \Modules\Accounting\Models\QuoteItem::create([
                'quote_id' => $quote->id,
                'title' => $item['title'] ?? 'Service',
                'description' => $item['description'] ?? '',
                'quantity' => $item['quantity'] ?? 1,
                'unit_price' => $item['unit_price'] ?? 0,
                'total' => ($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0),
            ]);
            
            $total += ($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0);
        }
        
        $quote->update(['subtotal' => $total, 'total_amount' => $total]);

        return ['content' => [['type' => 'text', 'text' => "Quote created: #{$quote->quote_number} (ID: {$quote->id}) for Client {$quote->client_name} (Total: {$total})"]]];
    }

    private function parseParameters($params)
    {
        $properties = [];
        foreach ($params as $key => $desc) {
            $parts = explode(':', $desc, 2);
            $type = $parts[0];
            $description = $parts[1] ?? '';
            $properties[$key] = [
                'type' => $type,
                'description' => trim($description)
            ];
        }
        return $properties;
    }
}
