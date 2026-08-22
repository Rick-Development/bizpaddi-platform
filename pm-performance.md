# PM Performance & Accountability Management System

## Product Requirements Document (PRD)

### 1. Purpose

The purpose of this system is to provide Rick Development with a structured, objective, and auditable way to measure Project Manager (PM) performance.

The system must measure actual project management performance, including:

* Developer task assignment and utilization
* Task delivery
* Deadline management
* PM review and verification
* Project progress
* Communication
* Blocker management
* Work discipline
* Reporting
* Accountability for delays

The system must clearly distinguish whether a delay was caused by the **PM, developer, client, external dependency, or an approved exception**.

A task being overdue must **not automatically result in a PM penalty**.

---

# 2. Core Principle

The system must measure:

> **Outcome, ownership, and accountability — not simply activity.**

Every task must have a clearly identifiable responsible party at every stage.

The system must be able to answer:

> Who was responsible for the task at this point?

> When did that responsibility begin?

> What was expected?

> What was the applicable SLA/deadline?

> Did the responsible person act within the required time?

> If there was a delay, who caused it?

> Was there a legitimate reason?

---

# 3. PM Performance Score

Every PM starts each monthly performance period with:

**100 points**

The performance engine calculates the final score based on six major categories.

| Category                             |   Weight |
| ------------------------------------ | -------: |
| Task Delivery & Deadline Management  |      40% |
| Project Progress & Management        |      20% |
| Communication & Responsiveness       |      15% |
| Developer Coordination & Utilization |      10% |
| Work Discipline & Check-ins          |      10% |
| Reporting & Documentation            |       5% |
| **Total**                            | **100%** |

The final standard performance score is between:

**0–100**

---

# 4. Performance Rating

|    Score | Rating            |
| -------: | ----------------- |
|   90–100 | Excellent         |
|    80–89 | Good              |
|    70–79 | Needs Improvement |
|    60–69 | Poor              |
| Below 60 | Critical          |

A PM falling below the configured performance threshold should automatically be flagged for management review.

---

# 5. PM Task Management Responsibility

The PM is responsible for ensuring that developers have meaningful work, understand what is required, and continue moving assigned work toward completion.

The PM is not responsible for performing the developer's technical work.

The PM is responsible for:

* Assigning tasks.
* Providing clear requirements.
* Setting appropriate deadlines.
* Monitoring progress.
* Following up with developers.
* Identifying blockers.
* Escalating blockers.
* Reviewing submitted work.
* Verifying whether requirements have been satisfied.
* Requesting changes where necessary.
* Confirming completed work.

---

# 6. Task Lifecycle

The task workflow shall be:

```text
TODO
  ↓
IN PROGRESS
  ↓
WORK SUBMITTED
  ↓
AWAITING PM REVIEW
  ↓
PM REVIEWING
  ↓
 ┌──────────────────────┐
 │                      │
 ▼                      ▼
APPROVED             CHANGES REQUESTED
 │                      │
 ▼                      ▼
DONE                 ASSIGNEE
                        │
                        ▼
                   WORK SUBMITTED
                        │
                        ▼
                  PM REVIEW AGAIN
```

This workflow applies to **any team member type** — developer, designer, UI/UX, or any other role.

There is **no separate QA role** in this workflow.

The PM is responsible for checking and verifying all submitted work, regardless of the team member's role.

---

# 7. Task Status Responsibility

Statuses apply to **any assigned team member** — developer, designer, UI/UX, or other roles.

| Status             | Responsible Party                    |
| ------------------ | ------------------------------------ |
| TODO               | PM                                   |
| IN PROGRESS        | Assignee (any role)                  |
| WORK SUBMITTED     | Assignee (any role)                  |
| AWAITING PM REVIEW | PM                                   |
| PM REVIEWING       | PM                                   |
| CHANGES REQUESTED  | Assignee (any role)                  |
| APPROVED           | PM                                   |
| DONE               | Completed                            |

---

# 8. Assignee Submission Does Not Mean Task Completion

When a team member (developer, designer, UI/UX, or any other role) marks their work as submitted, the task is **not automatically considered completed**.

Instead:

```text
Assignee completes work
        ↓
WORK SUBMITTED
        ↓
AWAITING PM REVIEW
        ↓
PM verifies work
        ↓
APPROVED
        ↓
DONE
```

The task only becomes officially completed after PM verification.

This prevents any team member from artificially improving completion statistics by marking unfinished work as submitted or done.

---

# 9. PM Review SLA

When a developer submits a task for review:

**Default PM Review SLA: 24 hours**

The SLA should be configurable by administration.

Example:

```text
August 16 — 2:30 PM
Developer submits task

August 16 — 2:30 PM
PM review responsibility begins

August 17 — 2:30 PM
PM review SLA expires

August 20
PM finally reviews
```

The system should calculate the actual review delay.

In this example:

**PM Review Delay ≈ 3 days beyond the SLA**

This becomes a PM accountability event.

---

# 10. PM Review Accountability

If the developer has submitted a task and the PM does not review it within the configured SLA:

> **PM Review SLA Breach**

The PM should receive the applicable performance penalty.

The system must not penalize the developer during this period because the developer has already completed their responsibility and submitted the work for review.

---

# 11. Changes Requested

If the PM reviews the task and determines that the requirements have not been satisfied:

```text
AWAITING PM REVIEW
        ↓
PM REVIEWING
        ↓
CHANGES REQUESTED
```

Responsibility immediately moves back to the assignee (developer, designer, UI/UX, or other role).

The PM review SLA stops.

The assignee becomes responsible for making the requested changes.

Example:

```text
Aug 16
Assignee submits work

Aug 17
PM reviews

Aug 17
PM requests changes

Aug 17–19
Assignee makes changes

Aug 19
Assignee resubmits

Aug 19
PM review SLA starts again
```

The assignee's delay between August 17 and August 19 is an assignee accountability event, not a PM review delay.

---

# 12. Accountability Ownership

The system must track responsibility throughout the entire task lifecycle.

For every task status period, the system should record:

* Task ID
* Previous status
* New status
* Person who changed the status
* Responsible user
* Responsible role
* Start time
* End time
* SLA deadline
* Completion time
* Delay reason
* Delay owner
* Whether SLA was breached

This creates a complete accountability history.

---

# 13. Accountability Example

### Task #123

```text
Aug 14 — Developer starts task
Owner: Developer

Aug 16 — Developer submits task
Owner changes → PM

Aug 17 — PM review SLA expires
Owner: PM
SLA: BREACHED

Aug 18 — PM reminder generated

Aug 20 — PM reviews task
Actual review: ~92 hours
Required SLA: 24 hours

Result:
PM delay identified
PM performance event created
```

The system can therefore prove that the PM, rather than the developer, caused the review delay.

---

# 14. Assignee Delay vs PM Delay

### Assignee Delay

The assignee (developer, designer, UI/UX, or any other role) is responsible when:

* The task is still `IN PROGRESS`.
* The assignee misses the assigned deadline.
* The assignee fails to provide the expected work.
* The assignee remains inactive without justification.
* The assignee takes excessive time to implement requested changes.

The PM should not automatically receive the assignee's delay penalty.

---

### PM Delay

The PM is responsible when:

* The assignee has submitted the work (`WORK SUBMITTED`).
* The task is in `AWAITING PM REVIEW`.
* The PM does not review within the configured SLA.
* The PM fails to follow up on assignee progress.
* The PM fails to escalate known blockers.
* The PM allows a project to remain stagnant without action.

---

# 15. External/Client Delay

The PM should not be penalized where a legitimate external dependency is responsible and the PM has properly documented and escalated the issue.

Examples:

* Client has not provided required information.
* Client has not approved a design.
* Third-party API is unavailable.
* Payment provider is experiencing an outage.
* External service is unavailable.
* Management approval is pending.

The task should be moved into an appropriate blocked/dependency state.

---

# 16. Blocked Tasks

Blocked tasks must require a documented reason.

Examples:

* Client response required.
* API dependency.
* Third-party provider issue.
* Design dependency.
* Technical blocker.
* Management approval.
* Developer availability.
* External service outage.

The system should record:

```text
blocked_at
blocked_by
blocker_reason
expected_resolution
resolved_at
```

---

# 17. Blocker Management

A PM is responsible for actively managing blockers.

Example:

```text
Developer blocked
       ↓
PM notified
       ↓
PM escalates immediately
       ↓
Client/API dependency
       ↓
Waiting for external response
```

The PM should not be penalized.

However:

```text
Developer blocked
       ↓
PM notified
       ↓
PM does nothing for several days
       ↓
No escalation
```

This is a PM performance issue.

---

# 18. Team Task Assignment Requirement

Every PM is expected to actively coordinate all team members — developers, designers, UI/UX, and other roles.

The system should track the number of valid tasks assigned by each PM to team members every month.

### Minimum Target

**50 valid team tasks per month**

### Performance Levels

| Monthly Team Tasks Assigned | Assessment          |
| --------------------------: | ------------------- |
|                        100+ | Excellent           |
|                       75–99 | Strong              |
|                       50–74 | Meets Minimum       |
|                       30–49 | Needs Attention     |
|                    Below 30 | Performance Concern |

The target must remain configurable based on the size of the PM's team, the team member roles involved, and active project workload.

---

# 19. What Counts as a Valid Team Task?

A task counts toward the PM's monthly task target only if:

* It is assigned to a team member (developer, designer, UI/UX, or any other role).
* It belongs to an active project.
* It represents meaningful work appropriate to the assignee's role.
* It has a meaningful title.
* It contains sufficient requirements/instructions.
* It is not duplicated.
* It is not cancelled.
* It is not a test task.
* It is not an administrative placeholder.

The system must prevent PMs from creating meaningless tasks solely to meet the monthly target.

The system must not count tasks assigned to the PM themselves toward the team assignment target.

---

# 20. Task Volume Must Not Be the Only Metric

The PM must not receive a high performance score merely because they assign many tasks.

The system must also track:

```text
Tasks Assigned
Tasks Started
Tasks Completed
Tasks Awaiting PM Review
Tasks Blocked
Tasks Overdue
Tasks Returned for Changes
Tasks Cancelled
```

Example:

### Healthy PM

```text
100 assigned
75 started
65 completed
10 in progress
5 blocked
```

### Poor PM

```text
100 assigned
20 started
10 completed
70 sitting in TODO
20 overdue
```

The second PM should receive a poor developer-coordination/project-management score despite meeting the 100-task assignment target.

---

# 21. Team Utilization

The system should monitor whether all team members — developers, designers, UI/UX, and others — have meaningful work.

Track:

* Team members with no active tasks.
* Team members with excessive idle periods.
* Team members with too many simultaneous tasks.
* Team members blocked for extended periods.
* Tasks sitting in TODO.
* Tasks awaiting PM review.
* Tasks repeatedly returned for changes.

The PM should receive alerts where any team member has no meaningful active work for a configured period.

Utilization should be tracked per role so that designer idle time and developer idle time can be reported separately.

---

# 22. Task Delivery & Deadline Management — 40%

This category evaluates whether projects and tasks are progressing toward their expected deadlines.

### Positive factors

* Early task completion.
* On-time task completion.
* Milestone delivery.
* Consistent project movement.

### Negative factors

* Unmanaged overdue tasks.
* Missed project deadlines.
* Failure to monitor deadlines.
* Failure to follow up with developers.
* Failure to escalate delivery risks.
* Repeated unjustified deadline extensions.

A developer missing a deadline should not automatically penalize the PM.

The system must first determine whether the PM properly managed and escalated the issue.

---

# 23. Project Progress & Management — 20%

The PM is responsible for maintaining project momentum.

The system should monitor:

* Project milestones.
* Milestone deadlines.
* Project activity.
* Completed tasks.
* Overdue tasks.
* Blockers.
* Developer activity.
* Client dependencies.
* Project stagnation.

---

# 24. Project Stagnation

A project with no meaningful activity should trigger an alert.

Suggested defaults:

```text
3 business days without meaningful activity
→ Warning

5+ business days
→ Performance concern
```

Projects intentionally paused or waiting on documented external dependencies must be excluded.

---

# 25. Communication & Responsiveness — 15%

The system should measure:

* Client response time.
* Internal team response time.
* Unanswered messages.
* Missed communication.
* Delayed escalation.
* Missing project updates.
* Failure to communicate blockers.

Communication SLAs should be configurable.

---

# 26. Team Coordination & Utilization — 10%

This category evaluates:

* Number of meaningful tasks assigned to team members (all roles).
* Team member utilization across all roles (developer, designer, UI/UX, other).
* Task distribution across team members.
* Assignee follow-up.
* Blocker management.
* PM review turnaround.
* Team member inactivity.
* Task flow.

The 50+ monthly team task assignment requirement contributes to this category.

---

# 27. Work Discipline & Check-ins — 10%

The existing work-log system remains part of performance tracking.

Track:

* Daily check-ins.
* Work sessions.
* Missed check-ins.
* Abnormally long sessions.
* Force checkout.
* Approved exceptions.

Existing performance rules may include:

* Missed weekday check-in → penalty.
* Work session exceeding 14 hours → penalty.
* System force checkout → penalty.

The system must exclude legitimate exceptions such as:

* Sundays.
* Approved leave.
* Approved absence.
* Applicable public holidays.
* Management-approved exceptions.

---

# 28. Reporting & Documentation — 5%

PMs must maintain:

* Project status.
* Weekly updates.
* Risks.
* Blockers.
* Client updates.
* Milestone updates.
* Developer performance concerns.

Missing required reporting should affect the reporting score.

---

# 29. Performance Calculation Must Be Idempotent

The existing `performance:sync` command may continue running hourly.

However, every synchronization must recalculate performance from the underlying performance events.

It must not repeatedly apply the same penalty every hour.

### Correct

```text
Base = 100

PM review breach = -3
Missed check-in = -2

Final = 95
```

An hourly synchronization still returns:

```text
95
```

unless a new performance event occurs.

### Incorrect

```text
Hour 1 = 95
Hour 2 = 92
Hour 3 = 89
Hour 4 = 86
```

The same event must never be repeatedly penalized simply because the scheduler ran.

---

# 30. Score Audit Trail

Every score adjustment must be explainable.

The system should store:

```text
performance_id
user_id
metric
event_type
points
reason
task_id
project_id
calculated_at
```

Example:

```text
Event:
PM Review SLA Breach

Task:
#123

Required SLA:
24 hours

Actual:
92 hours

Penalty:
-3

Date:
August 20, 2026
```

Administrators must be able to identify exactly why a PM received a particular score.

---

# 31. Task Completion Data Integrity

Whenever:

```text
status = DONE
```

the system must ensure:

```text
completed_at != NULL
```

When a PM approves the task:

```text
APPROVED
   ↓
DONE
   ↓
completed_at = current timestamp
```

A task marked as developer-completed but not verified by the PM must not be considered officially completed.

---

# 32. Deadline Change Audit

Any deadline change must be logged.

The system should record:

```text
Original deadline
New deadline
Changed by
Changed at
Reason
Approved by
```

Repeated unjustified deadline extensions should become a PM performance event.

---

# 33. Anti-Gaming Rules

The system must prevent manipulation of performance metrics.

The system must not reward:

* Duplicate tasks.
* Empty tasks.
* Meaningless micro-tasks.
* Artificial task splitting.
* Closing incomplete tasks.
* Unjustified deadline changes.
* Reassigning tasks solely to avoid accountability.

The number of tasks is only one performance indicator.

---

# 34. Approved Exceptions

Administrators must be able to create approved exceptions.

Examples:

* Approved leave.
* Public holiday.
* Client dependency.
* Third-party outage.
* Project officially paused.
* Management-approved delay.
* Other documented external dependency.

Each exception should contain:

```text
reason
approved_by
start_date
end_date
created_at
```

Applicable performance timers should pause during approved exceptions.

---

# 35. Performance Dashboard

The administrator dashboard should show:

```text
PM Name
Performance Score
Rating
Developer Tasks Assigned
Tasks Started
Tasks Completed
Tasks Overdue
Tasks Awaiting PM Review
PM Review SLA Breaches
Blocked Tasks
Stagnant Projects
Developer Utilization
Missed Check-ins
Communication Breaches
Reporting Compliance
```

Example:

```text
PM: John Doe

Performance: 74/100
Rating: NEEDS IMPROVEMENT

Developer Tasks: 82
Started: 70
Completed: 61
Overdue: 9
Awaiting PM Review: 4
PM Review SLA Breaches: 3
Blocked Tasks: 6
Stagnant Projects: 1
Missed Check-ins: 2

Primary Concern:
PM review delays
```

---

# 36. Task Accountability Timeline

Administrators must be able to open any task and see its complete accountability history.

Example:

```text
TASK #123

Developer:
Isaac

PM:
John

Aug 14
Developer started

Aug 16
Developer completed/submitted

Aug 16
PM review responsibility started

Aug 17
PM review SLA breached

Aug 18
PM reminder generated

Aug 20
PM reviewed

Result:
PM delay = 3 days beyond SLA
Performance Event = YES
```

---

# 37. Automated Alerts

### PM Alerts

* Task approaching deadline.
* Developer task overdue.
* PM review approaching SLA.
* PM review SLA breached.
* Project becoming stagnant.
* Developer has no meaningful active task.
* Developer blocked.
* Client response overdue.

### Admin Alerts

* PM performance below threshold.
* Repeated PM review SLA breaches.
* Excessive overdue tasks.
* PM below minimum task assignment target.
* Low developer utilization.
* Project stagnation.
* Repeated communication failures.

---

# 38. Performance Synchronization

The existing:

```text
performance:sync
```

should continue to run periodically.

Recommended:

**Hourly synchronization**

Performance calculation may also be triggered by:

* Work-log completion.
* Task status changes.
* Task completion.
* PM approval.
* SLA breach.
* Project milestone changes.

All calculations must remain idempotent.

---

# 39. Monthly Performance Lock

At the end of each month, the performance record should be finalized and locked.

Example:

```text
August 2026
Final Score: 82
Status: CLOSED
```

Only authorized administrators may modify a closed performance record.

Any manual adjustment must record:

```text
Administrator
Old score
New score
Reason
Timestamp
```

---

# 40. Recommended Configurable Performance Events

The following values are recommended starting points and should be configurable from the administration panel.

| Event                                  | Suggested Impact |
| -------------------------------------- | ---------------: |
| PM review SLA breach                   |               -3 |
| PM review >48 hours late               |    Additional -2 |
| Unmanaged overdue project              |               -5 |
| Missed check-in                        |               -2 |
| Failure to escalate blocker            |               -3 |
| Stagnant project                       |               -5 |
| Missing report                         |               -2 |
| Developer left without meaningful task |               -2 |
| Early task completion                  |               +1 |
| Strong milestone delivery              |               +2 |

The actual scoring values should be configurable without requiring code changes.

---

# 41. Technical Requirements

The Laravel implementation should provide:

### Performance Engine

The `PerformanceEngine` must calculate performance using actual accountability events.

It must consider:

* Task ownership.
* Task status history.
* Task SLAs.
* PM review SLAs.
* Developer delivery.
* Project progress.
* Communication.
* Developer utilization.
* Work logs.
* Reporting.
* Approved exceptions.

### Performance Sync

`performance:sync` must be idempotent and safe to run repeatedly.

### Event Tracking

All important task transitions must create auditable events.

### Data Integrity

Task completion must be tied to PM verification.

### Auditability

Every performance penalty or bonus must have a reason and source event.

---

# 42. Implementation Phases

## Phase 1 — Accountability Foundation

Implement:

1. Task ownership history.
2. Status transition history.
3. Responsibility timestamps.
4. SLA tracking.
5. Delay ownership.
6. Approved exceptions.
7. Accountability audit trail.

## Phase 2 — PM Performance

Implement:

1. Task delivery scoring.
2. PM review SLA.
3. Developer task assignment target.
4. Developer utilization.
5. Project stagnation.
6. Blocker management.

## Phase 3 — Management Controls

Implement:

1. Communication tracking.
2. Reporting compliance.
3. Automated alerts.
4. Performance dashboard.
5. Monthly score locking.
6. Manual adjustment audit trail.

## Phase 4 — Analytics

Implement:

1. PM performance history.
2. Developer utilization trends.
3. Project risk scoring.
4. PM comparison.
5. Management performance reports.

---

# 43. Final Acceptance Criteria

The system will be considered successful when management can answer the following directly from the system.

### PM Performance

* What is the PM's current score?
* Why is the PM's score at that level?
* How many developers are they managing?
* How many meaningful developer tasks did they assign?
* How many tasks were completed?
* How many are overdue?
* How many are awaiting PM review?
* How many PM review SLAs were breached?
* Which projects are stagnant?
* How many blockers were escalated?
* How responsive is the PM?
* Did the PM maintain required reports?

### Task Accountability

For any delayed task:

* Who owned it?
* When did they become responsible?
* What was the deadline/SLA?
* When did the SLA expire?
* Who actually caused the delay?
* Was the responsible person notified?
* Was there an approved exception?
* Was the issue escalated?
* Should the PM or developer receive the performance event?

The system must provide an evidence-based answer to each question.

---

# 44. Final Business Objective

The system must ensure that PMs cannot hide poor project management behind activity, while developers cannot hide poor delivery behind PM accountability.

The intended operating model is:

**PM assigns meaningful work → Developer executes → Developer submits → PM reviews and verifies → PM requests changes or approves → Task becomes Done → Performance system records accountability.**

The ultimate objective is to ensure that PMs:

**Assign work → keep developers productive → monitor deadlines → follow up → review submissions promptly → resolve blockers → communicate effectively → maintain project momentum → deliver projects.**

At the same time, developers remain individually accountable for the technical work assigned to them.

This creates a **fair, objective, auditable, and management-ready PM performance system** for Rick Development.

