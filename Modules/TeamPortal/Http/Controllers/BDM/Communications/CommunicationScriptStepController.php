<?php

namespace Modules\TeamPortal\Http\Controllers\BDM\Communications;

use App\Http\Controllers\Controller;
use Modules\Communications\Models\CommunicationScriptStep;
use Modules\Communications\Models\CommunicationScriptException;
use Illuminate\Http\Request;

class CommunicationScriptStepController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'script_id' => 'required',
                'order' => 'required|integer',
                'name' => 'required|string',
                'content' => 'nullable',
                'exceptions' => 'nullable|array',
            ]);

            $step = CommunicationScriptStep::create($validated);

            if (!empty($validated['exceptions'])) {
                foreach ($validated['exceptions'] as $exData) {
                    if (!empty($exData['trigger_text']) && !empty($exData['response_content'])) {
                        $step->exceptions()->create([
                            'trigger_text' => $exData['trigger_text'],
                            'response_content' => $exData['response_content'],
                            'action' => $exData['action'] ?? null,
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'step' => $step->load('exceptions'),
                'message' => 'Step created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, CommunicationScriptStep $step)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'nullable|string',
            'exceptions' => 'nullable|array',
        ]);

        $step->update([
            'name' => $validated['name'],
            'content' => $validated['content'] ?? '',
        ]);

        // Sync Exceptions
        if (isset($validated['exceptions'])) {
            // Very simple sync plan: Delete all and recreate. 
            // Or careful update. For simplicity in this user request, delete/recreate is robust enough for small lists.
            $step->exceptions()->delete();

            foreach ($validated['exceptions'] as $exData) {
                if (!empty($exData['trigger_text']) && !empty($exData['response_content'])) {
                    $step->exceptions()->create([
                        'trigger_text' => $exData['trigger_text'],
                        'response_content' => $exData['response_content'],
                        'action' => $exData['action'] ?? null,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'step' => $step->load('exceptions'),
            'message' => 'Step updated successfully'
        ]);
    }

    public function destroy(CommunicationScriptStep $step)
    {
        $step->delete();
        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        $request->validate(['steps' => 'required|array']);

        foreach ($request->steps as $order => $stepId) {
            CommunicationScriptStep::where('id', $stepId)->update(['order' => $order + 1]);
        }

        return response()->json(['success' => true]);
    }

    // Exception Handling Methods
    public function storeException(Request $request, CommunicationScriptStep $step)
    {
        $validated = $request->validate([
            'trigger_text' => 'required|string|max:255',
            'response_content' => 'required|string',
            'action' => 'nullable|string',
        ]);

        $exception = $step->exceptions()->create($validated);

        return response()->json([
            'success' => true,
            'exception' => $exception,
            'message' => 'Exception added successfully'
        ]);
    }

    public function destroyException(CommunicationScriptException $exception)
    {
        $exception->delete();
        return response()->json(['success' => true]);
    }
}
