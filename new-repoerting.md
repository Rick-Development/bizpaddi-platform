## Developer — Check-in

**Project**
- Multi-select checklist, required. (KnoxxPay, Billway, Xenthar, etc.)

**Assigned Tasks**
- Multi-select checklist, required. Pulled from tasks currently assigned to this dev — no free-text entry.

**Tasks selected for this session**
- Confirm which of the above you're actually working on this session.

---

## Developer — Check-out (repeated per task touched)

**Task**
- multi select checklist, required. From tasks selected at check-in.

**Result**
- Single select, required:
  - Completed
  - Partial
  - Blocked

**What changed since your last update on this task?**
- Single select, required:
  - New development completed
  - New testing performed
  - Bug fixed
  - New issue discovered
  - Waiting on dependency
  - Waiting on client
  - Waiting on API/provider
  - No material progress *(selecting this opens a required text field: "Explain why")*

**Progress %**
- Number input, required. System shows: *Previous: X% → Current: Y% → Delta: Z%* automatically once entered.
- If delta = 0% for 2 sessions running → "no material progress" reason above becomes mandatory, not optional.

**Output produced**
- Multi-select, required (pick all that apply), each with a quantity field:
  - Bug fix (qty)
  - Feature/code (qty)
  - API integration (qty)
  - Test(s) executed (qty)
  - Documentation (qty)
  - Other (describe)

**Time spent on this task**
- *System-calculated, read-only.* Derived from `pm_time_logs` (TimeLog records for this task + user within the current WorkLog session window). Displayed as: `Xh Ym` with a session total across all tasks. No manual entry required.
- Backend: `Task::getTotalTimeSpentAttribute()` sums `duration_minutes` from `pm_time_logs` scoped to this session's `start_time → end_time` window on `work_logs`.

**Evidence**
- Link or file upload.
- **Required if Result = Completed.**
- **Required if this task has been reported as Partial for 3 or more consecutive sessions** *(system enforces — dev cannot submit without it).*
- Type select: PR / Commit / Staging URL / Screenshot / Video / Test result / Deployment reference
- *System note: If 3+ consecutive Partial sessions exist with no evidence, the Evidence field is marked required and a warning banner is shown: "This task has been Partial for X sessions with no evidence. Upload required."*

**Blocker** *(only shown if Result = Blocked)*
- Blocked By: Client / PM / QA / API Provider / Third Party / Infrastructure / Requirement / Internal Dependency — required
- Blocking Item: short text, required
- Since: date, auto-filled or editable
- Expected Resolution: date
- Owner: who is responsible for resolving it

**Next action**
- Short text, required. What happens next on this task.

**Session declaration** *(shown once, not per task)*
- Checkbox: "I confirm this report reflects the actual work performed, discloses any blocker, and includes available evidence where required."

---

## PM — Check-in

**Project(s)**
- Multi-select, required.

**PM activity type for this session**
- Multi select:
  - Task Planning
  - Task Assignment
  - Requirement Analysis
  - Developer Follow-up
  - QA Coordination
  - Client Coordination
  - Internal Meeting
  - Client Meeting
  - Progress/Delivery Review
  - Blocker Resolution
  - Project Reporting
  - Other

**Tasks to assign this session**
- Multi-select checklist, **required**. Pulled from all unassigned or reassignable tasks across the PM's active projects — no free-text entry.
- PM selects every task they intend to assign during this session before checking in.
- Count is shown: *"X tasks selected for assignment."*
- At check-out, system compares tasks selected here vs. tasks actually assigned (from assignment activity log) and shows: *"X of Y planned assignments completed."* — gap is visible without the PM self-reporting.
- *Conditional:* field is always shown regardless of activity type. If PM selected zero tasks, they must confirm with a checkbox: "No task assignments planned this session."

**Session goal**
- Short text, required. *"What will you close or move forward this session?"*
- Stored against the session. Displayed back to PM read-only at check-out alongside the system-computed activity summary, so they can self-assess against their stated intent.

---

## PM — Client Request → Task Conversion (logged as it happens, not at check-out)

**Client Request description**
- Text, required.

**Classification**
- Single select, required: Bug / Feature / Change Request / Question / Support / Enhancement / Documentation / Other

**Action taken**
- Single select, required:
  - Create task(s) → opens task creation, linked to this request
  - Request clarification
  - Reject
  - Escalate
  - Add to backlog
  - No action required

**Linked Task(s)**
- Auto-populated if "Create task(s)" selected.

---

## PM — Review Queue (per item in queue, not a form per se — an action panel)

For each ReviewItem:
- **Action**, single select, required: Approve / Reject / Request Changes / Reassign Reviewer
- **Reason** — required if Reject or Request Changes: short text

---

## PM — Check-out

No free-text "what did you do today" field. Instead, the screen displays (read-only, system-computed):

- Tasks Assigned This Session
- Tasks Reviewed This Session
- Tasks Closed This Session
- Client Requests Received / Converted / Unconverted This Session
- Overdue Follow-ups

**Session goal recap** *(read-only, pulled from check-in)*
- Displays the PM's stated goal verbatim alongside the computed summary. No editing — the gap between intent and output is visible by design.

Plus two required qualitative fields only:
- **Key issues/decisions/risks** — short text
- **Requires management attention?** — Yes/No, if Yes: short text

