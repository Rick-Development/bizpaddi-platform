<?php

namespace Modules\AiEmployees\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AiEmployees\Models\AiEmployee;

class AiEmployeesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Root Primary Managers
        $roles = [
            'general_manager' => [
                'name' => 'General Manager',
                'prompt' => 'You are the General Manager AI. Your goal is to oversee all high-level objectives, strategy, and orchestrate the AI workforce.'
            ],
            'operations_manager' => [
                'name' => 'Operations Manager',
                'prompt' => 'You are the Operations Manager AI. Your goal is to handle internal operations, process monitoring, and team efficiency.'
            ],
            'business_development' => [
                'name' => 'Business Development AI',
                'prompt' => 'You are the Business Development Manager AI. Your goal is to forge strategic partnerships and discover new business opportunities.'
            ],
            'sales_marketing' => [
                'name' => 'Sales & Marketing AI',
                'prompt' => 'You are the Sales & Marketing Manager AI. Your goal is to drive revenue, qualify leads, and manage marketing campaigns.'
            ],
            'social_media' => [
                'name' => 'Social Media AI',
                'prompt' => 'You are the Social Media & Growth Manager AI. Your goal is to handle content creation, platform growth, and community engagement.'
            ],
            'engineering' => [
                'name' => 'Engineering Manager AI',
                'prompt' => 'You are the Engineering Manager AI. Your goal is to oversee architecture, code review, deployments, and technical sub-agents.'
            ],
            'designer' => [
                'name' => 'AI Designer',
                'prompt' => 'You are the AI Designer. Your goal is to create modern UI/UX designs, generate aesthetic concepts, and ensure visual consistency across all interfaces.'
            ],
            'project_manager' => [
                'name' => 'Project Manager AI',
                'prompt' => 'You are the Project Manager AI. Your goal is to manage projects, assign tasks to the engineering team based on workload, and ensure deadlines are met.'
            ],
            'finance_manager' => [
                'name' => 'Finance Manager AI',
                'prompt' => 'You are the Finance Manager AI. Your goal is to oversee invoicing, billing, quoting, and tracking revenue for the organization.'
            ],
        ];

        foreach ($roles as $roleKey => $data) {
            AiEmployee::updateOrCreate(
                ['role' => $roleKey],
                [
                    'name' => $data['name'],
                    'description' => $data['name'] . ' assigned to ' . str_replace('_', ' ', $roleKey) . ' operations.',
                    'system_prompt' => $data['prompt'],
                    'is_active' => true,
                    'is_sub_agent' => false,
                    'parent_id' => null,
                ]
            );
        }

        // 2. Sub-Intelligences
        $subIntelligences = [
            'general_manager' => [
                [
                    'name' => 'Business Intelligence',
                    'role' => 'business_intelligence',
                    'prompt' => 'You are the Business Intelligence AI. Your task is to analyze company performance, trends, metrics, and operational data.'
                ],
                [
                    'name' => 'Performance Intelligence',
                    'role' => 'performance_intelligence',
                    'prompt' => 'You are the Performance Intelligence AI. Your task is to measure department and AI employee performance against defined KPIs.'
                ],
                [
                    'name' => 'Strategy Intelligence',
                    'role' => 'strategy_intelligence',
                    'prompt' => 'You are the Strategy Intelligence AI. Your task is to analyze strategic opportunities, threats, priorities, and growth initiatives.'
                ],
                [
                    'name' => 'Decision Intelligence',
                    'role' => 'decision_intelligence',
                    'prompt' => 'You are the Decision Intelligence AI. Your task is to provide structured recommendations for management decisions.'
                ],
                [
                    'name' => 'Risk Intelligence',
                    'role' => 'risk_intelligence',
                    'prompt' => 'You are the Risk Intelligence AI. Your task is to identify business, operational, financial, technical, and commercial risks.'
                ],
                [
                    'name' => 'Cross-Department Coordination Intelligence',
                    'role' => 'cross_department_coordination',
                    'prompt' => 'You are the Cross-Department Coordination Intelligence AI. Your task is to ensure departments remain aligned and detect dependencies between teams.'
                ],
                [
                    'name' => 'Executive Reporting Intelligence',
                    'role' => 'executive_reporting',
                    'prompt' => 'You are the Executive Reporting Intelligence AI. Your task is to produce consolidated executive reports.'
                ],
                [
                    'name' => 'Resource Intelligence',
                    'role' => 'resource_intelligence',
                    'prompt' => 'You are the Resource Intelligence AI. Your task is to analyze staffing, engineering capacity, project capacity, and operational resources.'
                ],
            ],
            'operations_manager' => [
                [
                    'name' => 'Attendance Intelligence',
                    'role' => 'attendance_intelligence',
                    'prompt' => 'You are the Attendance Intelligence AI. Your task is to monitor team attendance and identify anomalies.'
                ],
                [
                    'name' => 'Task Monitoring Intelligence',
                    'role' => 'task_monitoring_intelligence',
                    'prompt' => 'You are the Task Monitoring Intelligence AI. Your task is to track assigned tasks, progress, deadlines, and overdue work.'
                ],
                [
                    'name' => 'Project Monitoring Intelligence',
                    'role' => 'project_monitoring_intelligence',
                    'prompt' => 'You are the Project Monitoring Intelligence AI. Your task is to track active projects, milestones, dependencies, and delays.'
                ],
                [
                    'name' => 'Client Follow-Up Intelligence',
                    'role' => 'client_followup_intelligence',
                    'prompt' => 'You are the Client Follow-Up Intelligence AI. Your task is to monitor client communications, pending responses, promised updates, and unresolved requests.'
                ],
                [
                    'name' => 'Incident Intelligence',
                    'role' => 'incident_intelligence',
                    'prompt' => 'You are the Incident Intelligence AI. Your task is to detect operational and production incidents and coordinate escalation.'
                ],
                [
                    'name' => 'Priority Intelligence',
                    'role' => 'priority_intelligence',
                    'prompt' => 'You are the Priority Intelligence AI. Your task is to determine urgency and prioritize operational work.'
                ],
                [
                    'name' => 'Meeting Intelligence',
                    'role' => 'meeting_intelligence',
                    'prompt' => 'You are the Meeting Intelligence AI. Your task is to capture meeting outcomes, decisions, requirements, and action items.'
                ],
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
                ],
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
                    'prompt' => 'You are the Opportunity Intelligence AI. Your task is to identify what Rick Development can potentially sell to a prospect.'
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
                ],
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
                ],
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
                ],
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
                ],
                [
                    'name' => 'Performance Intelligence',
                    'role' => 'engineering_performance_intelligence',
                    'prompt' => 'You are the Performance Intelligence AI. Your task is to identify performance bottlenecks and system lag issues.'
                ],
                [
                    'name' => 'Security Intelligence',
                    'role' => 'security_intelligence',
                    'prompt' => 'You are the Security Intelligence AI. Your task is to review applications and infrastructure for security risks.'
                ],
                [
                    'name' => 'DevOps Intelligence',
                    'role' => 'devops_intelligence',
                    'prompt' => 'You are the DevOps Intelligence AI. Your task is to coordinate deployment, CI/CD, environments, monitoring, and infrastructure.'
                ],
                [
                    'name' => 'Technical Debt Intelligence',
                    'role' => 'technical_debt_intelligence',
                    'prompt' => 'You are the Technical Debt Intelligence AI. Your task is to identify accumulated technical debt and recommend remediation.'
                ],
            ]
        ];

        foreach ($subIntelligences as $parentRole => $subs) {
            $parent = AiEmployee::where('role', $parentRole)->first();
            if ($parent) {
                foreach ($subs as $sub) {
                    AiEmployee::updateOrCreate(
                        ['role' => $sub['role']],
                        [
                            'name' => $sub['name'],
                            'description' => "Specialized sub-intelligence for {$parent->name}.",
                            'system_prompt' => $sub['prompt'],
                            'parent_id' => $parent->id,
                            'is_sub_agent' => true,
                            'is_active' => true
                        ]
                    );
                }
            }
        }
    }
}
