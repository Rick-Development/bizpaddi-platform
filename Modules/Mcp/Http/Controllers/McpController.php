<?php

namespace Modules\Mcp\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Mcp\Services\McpService;

class McpController extends Controller
{
    protected $mcpService;

    public function __construct(McpService $mcpService)
    {
        $this->mcpService = $mcpService;
    }

    public function index()
    {
        $tools = $this->mcpService->getTools();
        return view('mcp::index', compact('tools'));
    }

    public function executeAction(Request $request)
    {
        $tool = $request->input('tool');
        $params = $request->except(['_token', 'tool']);
        
        try {
            $result = $this->mcpService->executeTool($tool, $params);
            return redirect()->route('admin.mcp.index')->with('success', 'Action executed successfully: ' . ($result['content'][0]['text'] ?? 'Done'));
        } catch (\Exception $e) {
            return redirect()->route('admin.mcp.index')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function aiGenerator()
    {
        return view('mcp::ai-generator');
    }

    public function generateFromPrompt(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|min:10',
        ]);

        try {
            // Dispatch the job to process in background
            $userId = auth()->id() ?? 1; // Fallback to 1 if not authenticated (e.g. dev mode)
            \Modules\Mcp\Jobs\ProcessAIRequest::dispatch($request->prompt, $userId);
            
            return redirect()->route('admin.mcp.ai-generator')->with('success', '🤖 AI is processing your request in the background! Check back in a moment.');
        } catch (\Exception $e) {
            return redirect()->route('admin.mcp.ai-generator')->with('error', 'Error queuing request: ' . $e->getMessage());
        }
    }

    /**
     * Handle MCP JSON-RPC requests
     */
    public function handle(Request $request)
    {
        // Simple implementation of MCP protocol handling
        // Real implementation would handle JSON-RPC structure strictly
        
        $method = $request->input('method');
        
        try {
            switch ($method) {
                case 'tools/list':
                    return response()->json([
                        'tools' => $this->mcpService->getTools()
                    ]);
                
                case 'tools/call':
                    $params = $request->input('params');
                    $name = $params['name'];
                    $args = $params['arguments'];
                    
                    $result = $this->mcpService->executeTool($name, $args);
                    return response()->json($result);

                default:
                    return response()->json(['error' => 'Method not supported'], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
