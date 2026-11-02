<?php

namespace Modules\AiEmployees\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\AiEmployees\Models\AiEmployee;

class AiEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Only show root-level primary managers, never sub-intelligences in the main list
        $query = AiEmployee::whereNull('parent_id')->where('is_sub_agent', false)->withCount('subAgents');
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $employees = $query->get();
        $currentRole = $request->role;

        return view('aiemployees::admin.index', compact('employees', 'currentRole'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $parent = null;
        $subAgentTemplates = [];
        
        if ($request->filled('parent_id')) {
            $parent = AiEmployee::find($request->parent_id);
            if ($parent) {
                $subAgentTemplates = $this->getSubAgentTemplates($parent->role);
            }
        }
        
        return view('aiemployees::admin.create', compact('parent', 'subAgentTemplates'));
    }

    protected function getSubAgentTemplates($role)
    {
        $templates = [
            'operations_manager' => [
                [
                    'name' => 'KPI Intelligence',
                    'role' => 'kpi_intelligence',
                    'prompt' => 'You are the KPI Intelligence AI. Your task is to measure team and operational performance.'
                ],
                [
                    'name' => 'Accountability Intelligence',
                    'role' => 'accountability_intelligence',
                    'prompt' => 'You are the Accountability Intelligence AI. Your task is to compare reported work against actual evidence.'
                ],
                [
                    'name' => 'Operations Audit Intelligence',
                    'role' => 'operations_audit_intelligence',
                    'prompt' => 'You are the Operations Audit Intelligence AI. Your task is to independently challenge operational reports and request corrections where evidence does not support the claim.'
                ]
            ],
            'business_development' => [
                [
                    'name' => 'Market Discovery Intelligence',
                    'role' => 'market_discovery_intelligence',
                    'prompt' => 'You are the Market Discovery Intelligence AI. Your task is to identify attractive industries, markets, regions, and business segments.'
                ],
                [
                    'name' => 'Lead Discovery Intelligence',
                    'role' => 'lead_discovery_intelligence',
                    'prompt' => 'You are the Lead Discovery Intelligence AI. Your task is to find companies and organizations matching defined target profiles.'
                ],
                [
                    'name' => 'Contact Intelligence',
                    'role' => 'contact_intelligence',
                    'prompt' => 'You are the Contact Intelligence AI. Your task is to identify relevant decision-makers and available legitimate business contact channels.'
                ],
                [
                    'name' => 'Lead Enrichment Intelligence',
                    'role' => 'lead_enrichment_intelligence',
                    'prompt' => 'You are the Lead Enrichment Intelligence AI. Your task is to build a complete prospect profile.'
                ],
                [
                    'name' => 'Qualification Intelligence',
                    'role' => 'qualification_intelligence',
                    'prompt' => 'You are the Qualification Intelligence AI. Your task is to score prospects according to ICP, need, budget potential, authority, timing, and other criteria.'
                ],
                [
                    'name' => 'Opportunity Intelligence',
                    'role' => 'opportunity_intelligence',
                    'prompt' => 'You are the Opportunity Intelligence AI. Your task is to identify what Bizpaddi can potentially sell to a prospect.'
                ],
                [
                    'name' => 'Outreach Intelligence',
                    'role' => 'outreach_intelligence',
                    'prompt' => 'You are the Outreach Intelligence AI. Your task is to develop personalized outreach strategies.'
                ],
                [
                    'name' => 'Follow-Up Intelligence',
                    'role' => 'followup_intelligence',
                    'prompt' => 'You are the Follow-Up Intelligence AI. Your task is to maintain systematic prospect follow-up.'
                ],
                [
                    'name' => 'Partnership Intelligence',
                    'role' => 'partnership_intelligence',
                    'prompt' => 'You are the Partnership Intelligence AI. Your task is to identify strategic partnerships and channel opportunities.'
                ],
                [
                    'name' => 'Pipeline Intelligence',
                    'role' => 'pipeline_intelligence',
                    'prompt' => 'You are the Pipeline Intelligence AI. Your task is to track prospect progression from discovery through opportunity creation and conversion.'
                ]
            ],
            'sales_marketing' => [
                [
                    'name' => 'Sales Intelligence',
                    'role' => 'sales_intelligence',
                    'prompt' => 'You are the Sales Intelligence AI. Your task is to analyze opportunities, buying signals, objections, and deal progression.'
                ],
                [
                    'name' => 'Proposal Intelligence',
                    'role' => 'proposal_intelligence',
                    'prompt' => 'You are the Proposal Intelligence AI. Your task is to prepare proposals, quotations, scopes, and commercial materials.'
                ],
                [
                    'name' => 'Lead Nurturing Intelligence',
                    'role' => 'lead_nurturing_intelligence',
                    'prompt' => 'You are the Lead Nurturing Intelligence AI. Your task is to maintain relationships with prospects that are not yet ready to buy.'
                ],
                [
                    'name' => 'Follow-Up Intelligence',
                    'role' => 'sales_followup_intelligence',
                    'prompt' => 'You are the Follow-Up Intelligence AI. Your task is to track sales actions and prevent opportunities from becoming inactive.'
                ],
                [
                    'name' => 'Conversion Intelligence',
                    'role' => 'conversion_intelligence',
                    'prompt' => 'You are the Conversion Intelligence AI. Your task is to analyze funnel performance and identify conversion bottlenecks.'
                ],
                [
                    'name' => 'Market Intelligence',
                    'role' => 'sales_market_intelligence',
                    'prompt' => 'You are the Market Intelligence AI. Your task is to track market conditions and customer demand.'
                ],
                [
                    'name' => 'Campaign Intelligence',
                    'role' => 'campaign_intelligence',
                    'prompt' => 'You are the Campaign Intelligence AI. Your task is to plan and evaluate marketing campaigns.'
                ],
                [
                    'name' => 'Customer Segmentation Intelligence',
                    'role' => 'customer_segmentation_intelligence',
                    'prompt' => 'You are the Customer Segmentation Intelligence AI. Your task is to segment prospects and customers for targeted commercial activity.'
                ],
                [
                    'name' => 'Revenue Intelligence',
                    'role' => 'revenue_intelligence',
                    'prompt' => 'You are the Revenue Intelligence AI. Your task is to track expected and realized revenue.'
                ],
                [
                    'name' => 'Sales Audit Intelligence',
                    'role' => 'sales_audit_intelligence',
                    'prompt' => 'You are the Sales Audit Intelligence AI. Your task is to validate sales activity, pipeline claims, opportunity stages, and revenue reporting.'
                ]
            ],
            'social_media' => [
                [
                    'name' => 'Content Strategy Intelligence',
                    'role' => 'content_strategy_intelligence',
                    'prompt' => 'You are the Content Strategy Intelligence AI. Your task is to plan content themes, campaigns, and publishing priorities.'
                ],
                [
                    'name' => 'Copywriting Intelligence',
                    'role' => 'copywriting_intelligence',
                    'prompt' => 'You are the Copywriting Intelligence AI. Your task is to create platform-appropriate content.'
                ],
                [
                    'name' => 'Creative Intelligence',
                    'role' => 'creative_intelligence',
                    'prompt' => 'You are the Creative Intelligence AI. Your task is to develop creative concepts and briefs for graphics, videos, and campaigns.'
                ],
                [
                    'name' => 'Trend Intelligence',
                    'role' => 'trend_intelligence',
                    'prompt' => 'You are the Trend Intelligence AI. Your task is to identify relevant trends and growth opportunities.'
                ],
                [
                    'name' => 'Publishing Intelligence',
                    'role' => 'publishing_intelligence',
                    'prompt' => 'You are the Publishing Intelligence AI. Your task is to manage approved publishing schedules.'
                ],
                [
                    'name' => 'Engagement Intelligence',
                    'role' => 'engagement_intelligence',
                    'prompt' => 'You are the Engagement Intelligence AI. Your task is to monitor comments, replies, mentions, and community activity.'
                ],
                [
                    'name' => 'Community Intelligence',
                    'role' => 'community_intelligence',
                    'prompt' => 'You are the Community Intelligence AI. Your task is to identify important audience conversations and relationship opportunities.'
                ],
                [
                    'name' => 'Growth Intelligence',
                    'role' => 'growth_intelligence',
                    'prompt' => 'You are the Growth Intelligence AI. Your task is to analyze audience growth and experiment with acquisition strategies.'
                ],
                [
                    'name' => 'Social Analytics Intelligence',
                    'role' => 'social_analytics_intelligence',
                    'prompt' => 'You are the Social Analytics Intelligence AI. Your task is to measure reach, engagement, conversion, and content performance.'
                ],
                [
                    'name' => 'Brand Safety Intelligence',
                    'role' => 'brand_safety_intelligence',
                    'prompt' => 'You are the Brand Safety Intelligence AI. Your task is to review content and engagement actions for brand consistency and reputational risk.'
                ]
            ],
            'engineering' => [
                [
                    'name' => 'Architecture Intelligence',
                    'role' => 'architecture_intelligence',
                    'prompt' => 'You are the Architecture Intelligence AI. Your task is to review system architecture and technical decisions.'
                ],
                [
                    'name' => 'Code Review Intelligence',
                    'role' => 'code_review_intelligence',
                    'prompt' => 'You are the Code Review Intelligence AI. Your task is to review pull requests, code quality, security, maintainability, and standards.'
                ],
                [
                    'name' => 'Stack Intelligence',
                    'role' => 'stack_intelligence',
                    'prompt' => 'You are the Stack Intelligence AI. Your task is to select appropriate technologies for different project requirements.'
                ],
                [
                    'name' => 'Developer Coordination Intelligence',
                    'role' => 'developer_coordination_intelligence',
                    'prompt' => 'You are the Developer Coordination Intelligence AI. Your task is to coordinate developers and technical tasks.'
                ],
                [
                    'name' => 'Bug Intelligence',
                    'role' => 'bug_intelligence',
                    'prompt' => 'You are the Bug Intelligence AI. Your task is to classify, prioritize, assign, and track bugs.'
                ],
                [
                    'name' => 'QA Coordination Intelligence',
                    'role' => 'qa_coordination_intelligence',
                    'prompt' => 'You are the QA Coordination Intelligence AI. Your task is to coordinate testing and release readiness.'
                ]
            ]
        ];

        return $templates[$role] ?? [];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'description' => 'nullable|string',
            'system_prompt' => 'required|string',
            'ai_model_id' => 'nullable|integer',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|integer',
            'is_sub_agent' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_sub_agent'] = $request->input('is_sub_agent') == '1' || ($request->has('parent_id') && $request->filled('parent_id'));

        $employee = AiEmployee::create($validated);

        $redirectUrl = ($employee->is_sub_agent && $employee->parent_id) 
            ? route('admin.aiemployees.employees.edit', $employee->parent_id)
            : route('admin.aiemployees.employees.index', ['role' => $employee->role]);

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('success', 'AI Employee created successfully.');
            return response()->json([
                'success' => true,
                'redirect' => $redirectUrl
            ]);
        }

        return redirect()->to($redirectUrl)->with('success', 'AI Employee created successfully.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $employee = AiEmployee::findOrFail($id);
        return view('aiemployees::admin.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employee = AiEmployee::with(['subAgents', 'parent'])->findOrFail($id);
        return view('aiemployees::admin.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'description' => 'nullable|string',
            'system_prompt' => 'required|string',
            'ai_model_id' => 'nullable|integer',
            'is_active' => 'boolean',
            'parent_id' => 'nullable|integer',
            'is_sub_agent' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['is_sub_agent'] = $request->input('is_sub_agent') == '1' || ($request->has('parent_id') && $request->filled('parent_id'));

        $employee = AiEmployee::findOrFail($id);
        $employee->update($validated);

        $redirectUrl = ($employee->is_sub_agent && $employee->parent_id) 
            ? route('admin.aiemployees.employees.edit', $employee->parent_id)
            : route('admin.aiemployees.employees.index', ['role' => $employee->role]);

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('success', 'AI Employee updated successfully.');
            return response()->json([
                'success' => true,
                'redirect' => $redirectUrl
            ]);
        }

        return redirect()->to($redirectUrl)->with('success', 'AI Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $employee = AiEmployee::findOrFail($id);
        $role = $employee->role;
        $employee->delete();

        if ($request->ajax() || $request->wantsJson()) {
            session()->flash('success', 'AI Employee deleted successfully.');
            return response()->json([
                'success' => true,
                'redirect' => route('admin.aiemployees.employees.index', ['role' => $role])
            ]);
        }

        return redirect()->route('admin.aiemployees.employees.index', ['role' => $role])->with('success', 'AI Employee deleted successfully.');
    }

    /**
     * Toggle the active status of the specified employee.
     */
    public function toggleStatus(Request $request, $id)
    {
        $employee = AiEmployee::findOrFail($id);
        $employee->is_active = !$employee->is_active;
        $employee->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $employee->is_active,
                'message' => 'Status updated successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'AI Employee status updated.');
    }
}
