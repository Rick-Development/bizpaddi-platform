@extends('admin::layouts.app')

@section('title', 'Social Inbox')

@push('styles')
<style>
    .inbox-page { display:flex; flex-direction:column; gap:24px; }

    /* Topbar */
    .in-topbar {
        display:flex; justify-content:space-between; align-items:center;
        background:#0b1612; border:1px solid rgba(255,255,255,0.1); border-radius:18px;
        padding:20px 28px; box-shadow:0 4px 20px rgba(0,0,0,.25);
    }
    .in-topbar h1 { font-size:1.45rem; font-weight:800; color:#fff; display:flex; align-items:center; gap:10px; margin:0 0 4px; }
    .in-topbar h1 .accent { display:inline-block; width:5px; height:24px; background:linear-gradient(180deg,#10B981,#0E5E4A); border-radius:4px; }
    .in-topbar p { font-size:.85rem; color:#9ca3af; margin:0; }

    /* Buttons */
    .btn { display:inline-flex; align-items:center; gap:6px; padding:10px 20px; border-radius:12px; font-size:.85rem; font-weight:700; cursor:pointer; border:none; transition:all .2s; }
    .btn-ghost { background:rgba(255,255,255,0.06); color:#d1d5db; border:1px solid rgba(255,255,255,0.08); }
    .btn-ghost:hover { background:rgba(255,255,255,0.12); color:#fff; }
    .btn-primary { background:#0E5E4A; color:#fff; box-shadow:0 4px 14px rgba(14,94,74,.35); }
    .btn-primary:hover { background:#10B981; transform:translateY(-1px); }
    .btn-sm { padding:7px 14px; font-size:.78rem; }

    /* Stats */
    .inbox-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; }
    .ist-card { background:#0e1d17; border:1px solid rgba(255,255,255,0.1); border-radius:14px; padding:16px 18px; box-shadow:0 4px 16px rgba(0,0,0,.2); }
    .ist-card .is-icon { font-size:1.2rem; margin-bottom:8px; }
    .ist-card .is-val  { font-size:1.5rem; font-weight:800; color:#fff; }
    .ist-card .is-lbl  { font-size:.72rem; color:#9ca3af; margin-top:2px; font-weight:600; }

    /* Inbox layout: 3 cols */
    .inbox-layout {
        display: grid;
        grid-template-columns: 280px 1fr 300px;
        gap: 20px;
        height: calc(100vh - 340px);
        min-height: 560px;
    }

    /* Thread list */
    .thread-list {
        background:#0b1612; border:1px solid rgba(255,255,255,0.1); border-radius:16px;
        box-shadow:0 4px 16px rgba(0,0,0,.2); display:flex; flex-direction:column; overflow:hidden;
    }
    .tl-header { padding:14px 16px; border-bottom:1px solid rgba(255,255,255,0.08); }
    .tl-search { width:100%; padding:8px 12px; background:#08110e; border:1px solid rgba(255,255,255,0.1); border-radius:10px; font-size:.82rem; color:#fff; outline:none; transition:all .2s; }
    .tl-search:focus { border-color:#10B981; box-shadow:0 0 0 3px rgba(16,185,129,.15); }
    .tl-tabs { display:flex; gap:2px; margin-top:10px; background:rgba(255,255,255,0.06); border-radius:9px; padding:3px; }
    .tl-tab { flex:1; text-align:center; padding:5px 8px; font-size:.75rem; font-weight:700; border-radius:7px; cursor:pointer; color:#9ca3af; border:none; background:transparent; transition:all .2s; }
    .tl-tab.active { background:#10B981; color:#fff; box-shadow:0 1px 3px rgba(0,0,0,.3); }
    .tl-body { flex:1; overflow-y:auto; padding:8px; }

    /* Thread item */
    .thread-item {
        padding:10px 11px; border-radius:12px; cursor:pointer;
        transition:all .15s; border:1px solid transparent; margin-bottom:4px;
    }
    .thread-item:hover { background:rgba(255,255,255,0.04); }
    .thread-item.active { background:rgba(14,94,74,0.25); border-color:#10B981; }
    .thread-item.unread .ti-name { font-weight:800; }
    .ti-row1 { display:flex; justify-content:space-between; align-items:center; margin-bottom:3px; }
    .ti-badge { font-size:.65rem; font-weight:800; padding:2px 7px; border-radius:6px; }
    .ti-escalated { background:rgba(239,68,68,0.2); color:#f87171; }
    .ti-lead      { background:rgba(16,185,129,0.2); color:#34d399; }
    .ti-mention   { background:rgba(59,130,246,0.2); color:#60a5fa; }
    .ti-comment   { background:rgba(255,255,255,0.1); color:#9ca3af; }
    .ti-time { font-size:.68rem; color:#6b7280; font-weight:600; }
    .ti-name { font-size:.84rem; color:#f3f4f6; }
    .ti-preview { font-size:.74rem; color:#9ca3af; margin-top:2px; display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:2; overflow:hidden; }
    .ti-platform { font-size:.68rem; font-weight:800; margin-top:4px; }

    /* Chat panel */
    .chat-panel {
        background:#0b1612; border:1px solid rgba(255,255,255,0.1); border-radius:16px;
        box-shadow:0 4px 16px rgba(0,0,0,.2); display:flex; flex-direction:column; overflow:hidden;
    }
    .chat-header {
        padding:14px 18px; border-bottom:1px solid rgba(255,255,255,0.08);
        display:flex; justify-content:space-between; align-items:center;
        background:#0e1d17;
    }
    .ch-user h4 { font-size:.9rem; font-weight:800; color:#fff; margin:0 0 2px; }
    .ch-user p  { font-size:.74rem; color:#9ca3af; margin:0; }
    .chat-body { flex:1; overflow-y:auto; padding:20px; display:flex; flex-direction:column; gap:16px; background:#08110e; }

    /* Messages */
    .msg-row { display:flex; align-items:flex-start; gap:10px; }
    .msg-row.msg-outgoing { flex-direction:row-reverse; }
    .msg-avatar { width:34px; height:34px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.78rem; font-weight:800; flex-shrink:0; }
    .msg-bubble { max-width:75%; }
    .msg-bubble .mb-text { padding:10px 14px; border-radius:14px; font-size:.83rem; line-height:1.5; }
    .msg-incoming .mb-text { background:#0e1d17; border:1px solid rgba(255,255,255,0.1); color:#f3f4f6; border-bottom-left-radius:4px; }
    .msg-outgoing .mb-text { background:#0E5E4A; color:#fff; border-bottom-right-radius:4px; }
    .msg-bubble .mb-time { font-size:.65rem; color:#6b7280; margin-top:4px; font-weight:600; }
    .msg-outgoing .mb-time { text-align:right; }

    /* AI insight box */
    .ai-insight {
        background:linear-gradient(135deg,rgba(30,58,138,0.3),rgba(14,94,74,0.3));
        border:1px solid rgba(59,130,246,0.3); border-radius:14px;
        padding:14px 16px; margin-left:44px;
    }
    .ai-insight .ai-head { display:flex; align-items:center; gap:7px; font-size:.83rem; font-weight:800; color:#60a5fa; margin-bottom:6px; }
    .ai-insight p { font-size:.79rem; color:#93c5fd; margin:0 0 10px; line-height:1.5; }
    .ai-insight .ai-actions { display:flex; gap:8px; flex-wrap:wrap; }
    .ai-btn { padding:6px 14px; border-radius:9px; font-size:.75rem; font-weight:700; cursor:pointer; transition:all .15s; border:none; }
    .ai-btn-outline { background:rgba(255,255,255,0.06); border:1px solid rgba(59,130,246,0.3); color:#93c5fd; }
    .ai-btn-outline:hover { background:rgba(59,130,246,0.15); }
    .ai-btn-solid { background:#2563eb; color:#fff; }
    .ai-btn-solid:hover { background:#1d4ed8; }

    /* Reply area */
    .chat-footer { padding:14px 16px; border-top:1px solid rgba(255,255,255,0.08); background:#0e1d17; }
    .reply-box { width:100%; background:#08110e; border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:10px 14px; font-size:.83rem; color:#fff; resize:none; outline:none; transition:all .2s; font-family:inherit; }
    .reply-box:focus { border-color:#10B981; box-shadow:0 0 0 3px rgba(16,185,129,.15); }
    .chat-footer-actions { display:flex; justify-content:flex-end; gap:8px; margin-top:8px; }

    /* Right sidebar */
    .inbox-sidebar { display:flex; flex-direction:column; gap:14px; overflow-y:auto; }

    /* Contact card */
    .contact-card { background:#0b1612; border:1px solid rgba(255,255,255,0.1); border-radius:14px; padding:18px; box-shadow:0 4px 16px rgba(0,0,0,.2); }
    .cc-avatar { width:52px; height:52px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; font-weight:800; margin:0 auto 12px; }
    .cc-name { font-size:.9rem; font-weight:800; color:#fff; text-align:center; }
    .cc-handle { font-size:.78rem; color:#9ca3af; text-align:center; margin-top:2px; }
    .cc-badges { display:flex; justify-content:center; gap:6px; margin-top:10px; flex-wrap:wrap; }
    .cc-badge { font-size:.68rem; font-weight:800; padding:3px 9px; border-radius:7px; }
    .cc-divider { height:1px; background:rgba(255,255,255,0.08); margin:14px 0; }
    .cc-row { display:flex; justify-content:space-between; align-items:center; padding:5px 0; font-size:.8rem; }
    .cc-row .cc-key { color:#9ca3af; font-weight:600; }
    .cc-row .cc-val { font-weight:700; color:#f3f4f6; }
    .cc-actions { display:flex; flex-direction:column; gap:8px; margin-top:14px; }

    /* Side info card */
    .info-card { background:#0b1612; border:1px solid rgba(255,255,255,0.1); border-radius:14px; overflow:hidden; box-shadow:0 4px 16px rgba(0,0,0,.2); }
    .ic-header { padding:12px 16px; border-bottom:1px solid rgba(255,255,255,0.08); font-size:.85rem; font-weight:800; color:#fff; background:#0e1d17; }
    .ic-body { padding:12px 16px; color:#d1d5db; }
    .ic-item { display:flex; justify-content:space-between; padding:7px 0; border-bottom:1px solid #f7f7f7; font-size:.8rem; }
    .ic-item:last-child { border-bottom:none; padding-bottom:0; }
    .ic-item .ic-key { color:#aaa; font-weight:600; }
    .ic-item .ic-val { font-weight:700; color:#222; }
</style>
@endpush

@section('content')
<div class="inbox-page">

    {{-- Topbar --}}
    <div class="in-topbar">
        <div>
            <h1><span class="accent"></span> Social Inbox</h1>
            <p>Incoming leads, mentions, comments and messages — monitored autonomously by SMM AI.</p>
        </div>
        <div style="display:flex; gap:10px;">
            <button class="btn btn-ghost">Filter by Platform</button>
            <button class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Trigger AI Sweep
            </button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="inbox-stats">
        <div class="ist-card">
            <div class="is-icon">📥</div>
            <div class="is-val">{{ $totalUnread }}</div>
            <div class="is-lbl">Total Unread</div>
        </div>
        <div class="ist-card">
            <div class="is-icon">🔥</div>
            <div class="is-val">{{ $hotLeads }}</div>
            <div class="is-lbl">Hot Leads</div>
        </div>
        <div class="ist-card">
            <div class="is-icon">⚠️</div>
            <div class="is-val">{{ $escalated }}</div>
            <div class="is-lbl">Escalated</div>
        </div>
        <div class="ist-card">
            <div class="is-icon">✅</div>
            <div class="is-val">{{ $resolvedToday }}</div>
            <div class="is-lbl">Resolved Today</div>
        </div>
    </div>

    {{-- 3-panel inbox layout --}}
    <div class="inbox-layout">

        {{-- Thread list --}}
        <div class="thread-list">
            <div class="tl-header">
                <input class="tl-search" type="text" placeholder="Search interactions...">
                <div class="tl-tabs">
                    <button class="tl-tab active">All</button>
                    <button class="tl-tab">Leads</button>
                    <button class="tl-tab">Alerts</button>
                </div>
            </div>
            <div class="tl-body">
                @forelse($threads as $thread)
                @php $badge = $thread->category_badge; @endphp
                <div class="thread-item {{ $thread->status === 'unread' ? 'unread' : '' }} {{ $activeThread && $activeThread->id === $thread->id ? 'active' : '' }}">
                    <div class="ti-row1">
                        <span class="ti-badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        <span class="ti-time">{{ $thread->created_at->diffForHumans(null, true) }} ago</span>
                    </div>
                    <div class="ti-name">{{ $thread->sender_name ?? 'Unknown' }}</div>
                    <div class="ti-preview">{{ $thread->message }}</div>
                    <div class="ti-platform" style="color:#aaa;">{{ $thread->platform_label }}</div>
                </div>
                @empty
                <div style="text-align:center; padding:30px 16px; color:#bbb;">
                    <div style="font-size:1.8rem; margin-bottom:8px;">📥</div>
                    <p style="font-size:.8rem; margin:0;">Inbox is empty.<br>Run AI Sweep to detect interactions.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Chat panel --}}
        <div class="chat-panel">
            <div class="chat-header">
                <div class="ch-user">
                    <h4>@tech_investor</h4>
                    <p>X (Twitter) · Direct Mention · <span style="color:#b91c1c; font-weight:700;">High Priority</span></p>
                </div>
                <div style="display:flex; gap:8px;">
                    <button class="btn btn-ghost btn-sm">View Profile</button>
                    <button class="btn btn-sm" style="background:#dcfce7; color:#15803d;">Mark Resolved</button>
                </div>
            </div>

            <div class="chat-body">
                {{-- Incoming message --}}
                <div class="msg-row msg-incoming">
                    <div class="msg-avatar" style="background:#f3f4f6; color:#111;">TI</div>
                    <div class="msg-bubble">
                        <div class="mb-text">Our app keeps crashing on the new iOS beta, we need a developer ASAP to look at this. Are you guys taking new clients right now?</div>
                        <div class="mb-time">10:42 AM</div>
                    </div>
                </div>

                {{-- AI insight --}}
                <div class="ai-insight">
                    <div class="ai-head">🤖 SMM AI Analysis</div>
                    <p>This user is expressing technical urgency and immediate intent to hire. Profile analysis shows <strong>140K followers</strong> and active in the fintech/startup space. I've categorised this as a <strong>High Priority Lead</strong> and have flagged it for BDM handoff.</p>
                    <div class="ai-actions">
                        <button class="ai-btn ai-btn-outline">Handoff to BDM AI</button>
                        <button class="ai-btn ai-btn-outline">Research Profile</button>
                        <button class="ai-btn ai-btn-solid">Draft Reply</button>
                    </div>
                </div>

                {{-- Outgoing draft --}}
                <div class="msg-row msg-outgoing">
                    <div class="msg-avatar" style="background:#10231C; color:#fff;">RD</div>
                    <div class="msg-bubble">
                        <div class="mb-text">Hi! Thanks for reaching out. We'd love to help — iOS crash issues are something our team handles regularly. I'll have one of our senior devs reach out to you today. Can you DM us your contact details?</div>
                        <div class="mb-time">AI Draft · Not sent</div>
                    </div>
                </div>
            </div>

            <div class="chat-footer">
                <textarea class="reply-box" rows="3" placeholder="Type a reply... or let the AI generate one"></textarea>
                <div class="chat-footer-actions">
                    <button class="btn btn-ghost btn-sm">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        Generate with AI
                    </button>
                    <button class="btn btn-primary btn-sm">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Send Reply
                    </button>
                </div>
            </div>
        </div>

        {{-- Right sidebar --}}
        <div class="inbox-sidebar">
            {{-- Contact card --}}
            <div class="contact-card">
                <div class="cc-avatar" style="background:#f3f4f6; color:#111;">TI</div>
                <div class="cc-name">@tech_investor</div>
                <div class="cc-handle">X (Twitter)</div>
                <div class="cc-badges">
                    <span class="cc-badge" style="background:#fee2e2;color:#b91c1c;">Escalated</span>
                    <span class="cc-badge" style="background:#dcfce7;color:#15803d;">Hot Lead</span>
                </div>
                <div class="cc-divider"></div>
                <div class="cc-row"><span class="cc-key">Followers</span><span class="cc-val">140K</span></div>
                <div class="cc-row"><span class="cc-key">Platform</span><span class="cc-val">X (Twitter)</span></div>
                <div class="cc-row"><span class="cc-key">Detected</span><span class="cc-val">10 min ago</span></div>
                <div class="cc-row"><span class="cc-key">Priority</span><span class="cc-val" style="color:#b91c1c;">High</span></div>
                <div class="cc-actions">
                    <button class="btn btn-primary" style="width:100%; justify-content:center; font-size:.8rem; padding:9px;">Handoff to BDM</button>
                    <button class="btn btn-ghost" style="width:100%; justify-content:center; font-size:.8rem; padding:9px;">View Full Profile</button>
                </div>
            </div>

            {{-- Quick stats --}}
            <div class="info-card">
                <div class="ic-header">📊 Inbox Overview</div>
                <div class="ic-body">
                    <div class="ic-item"><span class="ic-key">Total Today</span><span class="ic-val">47</span></div>
                    <div class="ic-item"><span class="ic-key">Leads Found</span><span class="ic-val">12</span></div>
                    <div class="ic-item"><span class="ic-key">AI Handled</span><span class="ic-val">38</span></div>
                    <div class="ic-item"><span class="ic-key">Escalated</span><span class="ic-val" style="color:#b91c1c;">3</span></div>
                    <div class="ic-item"><span class="ic-key">Avg. Response</span><span class="ic-val">4 min</span></div>
                </div>
            </div>

            {{-- AI suggestion --}}
            <div style="background:linear-gradient(135deg,#10231C,#2d3185); border-radius:14px; padding:18px; color:#fff;">
                <div style="font-size:1.2rem; margin-bottom:8px;">🤖</div>
                <div style="font-size:.85rem; font-weight:800; margin-bottom:6px;">AI Recommendation</div>
                <p style="font-size:.78rem; color:rgba(255,255,255,.65); line-height:1.5; margin:0 0 12px;">Based on @tech_investor's profile, this is a high-value lead. Recommend BDM AI contact within the next 30 minutes.</p>
                <button style="width:100%; background:#0E5E4A; color:#fff; border:none; padding:9px; border-radius:9px; font-size:.8rem; font-weight:700; cursor:pointer;">Accept & Route to BDM</button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.tl-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.tl-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
    });
});
document.querySelectorAll('.thread-item').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.thread-item').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        this.classList.remove('unread');
    });
});
</script>
@endpush