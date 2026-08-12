@extends('admin::layouts.app')

@section('title', 'Content Calendar')

@push('styles')
<style>
    /* ─── Page wrapper ─────────────────────────────────── */
    .cal-page { display: flex; flex-direction: column; gap: 24px; }

    /* ─── Topbar ───────────────────────────────────────── */
    .cal-topbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 18px;
        padding: 20px 28px;
        box-shadow: 0 1px 6px rgba(0,0,0,.04);
    }
    .cal-topbar-left h1 {
        font-size: 1.45rem;
        font-weight: 800;
        color: #111;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 4px 0;
    }
    .cal-topbar-left h1 span.accent-bar {
        display: inline-block;
        width: 5px;
        height: 24px;
        background: linear-gradient(180deg, #FB4C4B, #191C47);
        border-radius: 4px;
    }
    .cal-topbar-left p { font-size: .85rem; color: #888; margin: 0; }
    .cal-topbar-right { display: flex; gap: 10px; align-items: center; }

    /* ─── Buttons ──────────────────────────────────────── */
    .btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 20px; border-radius: 12px; font-size: .85rem;
        font-weight: 700; cursor: pointer; border: none; transition: all .2s;
    }
    .btn-ghost {
        background: #f5f5f7; color: #444;
    }
    .btn-ghost:hover { background: #eee; }
    .btn-primary {
        background: #FB4C4B; color: #fff;
        box-shadow: 0 4px 14px rgba(251,76,75,.25);
    }
    .btn-primary:hover { background: #e83b3a; transform: translateY(-1px); }
    .btn-outline {
        background: transparent; color: #555;
        border: 1px solid #e0e0e0;
    }
    .btn-outline:hover { background: #f9f9f9; }

    /* ─── Platform filter pills ─────────────────────────── */
    .platform-filters {
        display: flex; gap: 8px; flex-wrap: wrap; align-items: center;
    }
    .pf-label { font-size: .75rem; font-weight: 700; color: #aaa; margin-right: 4px; }
    .pill {
        padding: 5px 14px; border-radius: 20px; font-size: .78rem;
        font-weight: 700; cursor: pointer; border: 2px solid transparent;
        transition: all .2s;
    }
    .pill.all    { background: #191C47; color: #fff; }
    .pill.li     { background: #e8f0fe; color: #1a73e8; border-color: #c5d8fb; }
    .pill.li:hover { background: #1a73e8; color:#fff; }
    .pill.x      { background: #f0f0f0; color: #111; border-color: #ddd; }
    .pill.x:hover { background: #111; color:#fff; }
    .pill.ig     { background: #fce4ec; color: #c2185b; border-color: #f8bbd0; }
    .pill.ig:hover { background: #c2185b; color:#fff; }
    .pill.fb     { background: #e3f2fd; color: #1565c0; border-color: #bbdefb; }
    .pill.fb:hover { background: #1565c0; color:#fff; }

    /* ─── Main layout: calendar + sidebar ──────────────── */
    .cal-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 24px;
        align-items: start;
    }

    /* ─── Calendar card ─────────────────────────────────── */
    .cal-card {
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 18px;
        box-shadow: 0 1px 6px rgba(0,0,0,.04);
        overflow: hidden;
    }
    .cal-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px 16px;
        border-bottom: 1px solid #f5f5f5;
    }
    .cal-nav h2 { font-size: 1.1rem; font-weight: 800; color: #111; margin: 0; }
    .cal-nav-btns { display: flex; gap: 8px; }
    .cal-nav-btns button {
        width: 36px; height: 36px; border-radius: 10px;
        border: 1px solid #e8e8e8; background: #fff;
        font-size: 1rem; cursor: pointer; display: flex;
        align-items: center; justify-content: center;
        color: #444; transition: all .2s;
    }
    .cal-nav-btns button:hover { background: #FB4C4B; color: #fff; border-color: #FB4C4B; }
    .today-btn {
        padding: 6px 14px; border-radius: 10px;
        border: 1px solid #e8e8e8; background: #fff;
        font-size: .8rem; font-weight: 700; cursor: pointer;
        color: #555; transition: all .2s;
    }
    .today-btn:hover { background: #f5f5f5; }

    /* ─── Day headers ────────────────────────────────────── */
    .cal-day-headers {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0;
        padding: 12px 16px 8px;
        background: #fafafa;
        border-bottom: 1px solid #f0f0f0;
    }
    .cal-day-headers div {
        text-align: center;
        font-size: .7rem;
        font-weight: 800;
        color: #bbb;
        letter-spacing: .08em;
        text-transform: uppercase;
    }
    .cal-day-headers div.weekend { color: #FB4C4B; }

    /* ─── Calendar grid ──────────────────────────────────── */
    .cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0;
        padding: 8px;
    }
    .cal-day {
        min-height: 100px;
        padding: 8px;
        border-radius: 10px;
        margin: 2px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all .15s;
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .cal-day:hover { background: #f9f9fb; border-color: #eee; }
    .cal-day.today { background: #fff8f8; border-color: #FB4C4B; }
    .cal-day.empty { opacity: .3; pointer-events: none; }
    .cal-day.weekend .day-num { color: #FB4C4B; }

    .day-num {
        font-size: .85rem;
        font-weight: 800;
        color: #222;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 26px; height: 26px;
        border-radius: 8px;
    }
    .cal-day.today .day-num {
        background: #FB4C4B;
        color: #fff;
    }

    /* ─── Post chips ─────────────────────────────────────── */
    .post-chip {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 3px 7px;
        border-radius: 6px;
        font-size: .68rem;
        font-weight: 700;
        line-height: 1.3;
        cursor: pointer;
        transition: all .15s;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .post-chip:hover { filter: brightness(.92); }
    .chip-li   { background: #dbeafe; color: #1d4ed8; }
    .chip-x    { background: #f3f4f6; color: #111; }
    .chip-ig   { background: #fce7f3; color: #be185d; }
    .chip-fb   { background: #dbeafe; color: #1e40af; }
    .chip-ai   { background: #ede9fe; color: #7c3aed; }
    .chip-dot  { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
    .dot-li    { background: #2563eb; }
    .dot-x     { background: #111; }
    .dot-ig    { background: #ec4899; }
    .dot-fb    { background: #3b82f6; }
    .dot-ai    { background: #8b5cf6; }

    .more-posts {
        font-size: .65rem;
        font-weight: 700;
        color: #aaa;
        padding: 1px 4px;
    }

    /* ─── Sidebar ────────────────────────────────────────── */
    .cal-sidebar { display: flex; flex-direction: column; gap: 16px; }

    .side-card {
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 16px;
        box-shadow: 0 1px 6px rgba(0,0,0,.04);
        overflow: hidden;
    }
    .side-card-header {
        padding: 16px 18px 12px;
        border-bottom: 1px solid #f5f5f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .side-card-header h3 {
        font-size: .9rem;
        font-weight: 800;
        color: #111;
        margin: 0;
    }
    .side-card-body { padding: 14px 18px; }

    /* ─── Upcoming posts list ─────────────────────────────── */
    .upcoming-post {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 10px 0;
        border-bottom: 1px solid #f5f5f5;
    }
    .upcoming-post:last-child { border-bottom: none; padding-bottom: 0; }
    .upcoming-date {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f5f5f7;
        flex-shrink: 0;
    }
    .upcoming-date .ud-day { font-size: .8rem; font-weight: 800; color: #111; line-height: 1; }
    .upcoming-date .ud-mon { font-size: .6rem; font-weight: 700; color: #aaa; line-height: 1; margin-top: 2px; }
    .upcoming-post-info { flex: 1; min-width: 0; }
    .upcoming-post-info .up-title { font-size: .82rem; font-weight: 700; color: #222; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .upcoming-post-info .up-meta { font-size: .72rem; color: #aaa; margin-top: 2px; display: flex; gap: 6px; align-items: center; }
    .up-platform-badge {
        font-size: .65rem;
        font-weight: 800;
        padding: 2px 7px;
        border-radius: 6px;
    }

    /* ─── AI assistant card ───────────────────────────────── */
    .ai-assist-card {
        background: linear-gradient(135deg, #191C47 0%, #2d3185 100%);
        border-radius: 16px;
        padding: 20px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .ai-assist-card::before {
        content: '';
        position: absolute;
        top: -20px; right: -20px;
        width: 100px; height: 100px;
        background: rgba(255,255,255,.06);
        border-radius: 50%;
    }
    .ai-assist-card::after {
        content: '';
        position: absolute;
        bottom: -30px; right: -10px;
        width: 130px; height: 130px;
        background: rgba(251,76,75,.12);
        border-radius: 50%;
    }
    .ai-assist-card h3 { font-size: .9rem; font-weight: 800; margin: 0 0 4px; }
    .ai-assist-card p  { font-size: .78rem; color: rgba(255,255,255,.65); margin: 0 0 14px; line-height: 1.5; }
    .ai-assist-card button {
        background: #FB4C4B;
        color: #fff;
        border: none;
        padding: 9px 18px;
        border-radius: 10px;
        font-size: .8rem;
        font-weight: 700;
        cursor: pointer;
        width: 100%;
        transition: all .2s;
        position: relative;
        z-index: 1;
    }
    .ai-assist-card button:hover { background: #e83b3a; }

    /* ─── Stats row ──────────────────────────────────────── */
    .cal-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }
    .cal-stat {
        background: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 14px;
        padding: 16px 18px;
        box-shadow: 0 1px 4px rgba(0,0,0,.03);
    }
    .cal-stat .cs-label { font-size: .7rem; font-weight: 700; color: #bbb; text-transform: uppercase; letter-spacing: .06em; }
    .cal-stat .cs-value { font-size: 1.6rem; font-weight: 800; color: #111; margin: 4px 0 0; }
    .cal-stat .cs-sub   { font-size: .72rem; color: #aaa; }

    /* ─── Badge ──────────────────────────────────────────── */
    .badge {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 3px 9px; border-radius: 8px; font-size: .7rem; font-weight: 700;
    }
    .badge-green { background: #dcfce7; color: #15803d; }
    .badge-red   { background: #fee2e2; color: #b91c1c; }
    .badge-blue  { background: #dbeafe; color: #1d4ed8; }
    .badge-purple { background: #ede9fe; color: #6d28d9; }
</style>
@endpush

@section('content')
@php
    // All variables passed from SocialMediaController::calendar()
    // $year, $month, $monthName, $firstDay, $daysInMonth, $today,
    // $monthPosts (Collection grouped by day), $upcomingPosts,
    // $totalScheduled, $aiGenerated, $publishedCount, $pendingReview

    $platformMeta = [
        'linkedin'  => ['chip-li', 'dot-li'],
        'x'         => ['chip-x',  'dot-x'],
        'instagram' => ['chip-ig', 'dot-ig'],
        'facebook'  => ['chip-fb', 'dot-fb'],
    ];
    $platformLabel = [
        'linkedin'  => 'LinkedIn',
        'x'         => 'X (Twitter)',
        'instagram' => 'Instagram',
        'facebook'  => 'Facebook',
    ];
    $badgeStyle = [
        'linkedin'  => 'background:#dbeafe;color:#1d4ed8;',
        'x'         => 'background:#f3f4f6;color:#111;',
        'instagram' => 'background:#fce7f3;color:#be185d;',
        'facebook'  => 'background:#eff6ff;color:#1d4ed8;',
    ];
@endphp
<div class="cal-page">

    {{-- ─── Topbar ─── --}}
    <div class="cal-topbar">
        <div class="cal-topbar-left">
            <h1>
                <span class="accent-bar"></span>
                Content Calendar
            </h1>
            <p>Plan, schedule and track social media posts across all platforms.</p>
        </div>
        <div class="cal-topbar-right">
            <div class="platform-filters">
                <span class="pf-label">Filter:</span>
                <span class="pill all">All</span>
                <span class="pill li">LinkedIn</span>
                <span class="pill x">X</span>
                <span class="pill ig">Instagram</span>
                <span class="pill fb">Facebook</span>
            </div>
            <button class="btn btn-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h8m-8 6h16"/></svg>
                List View
            </button>
            <button class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Schedule Post
            </button>
        </div>
    </div>

    {{-- ─── Stats Row ─── --}}
    <div class="cal-stats">
        <div class="cal-stat">
            <div class="cs-label">This Month</div>
            <div class="cs-value">{{ $totalScheduled }}</div>
            <div class="cs-sub">Posts scheduled</div>
        </div>
        <div class="cal-stat">
            <div class="cs-label">AI Generated</div>
            <div class="cs-value">{{ $aiGenerated }}</div>
            <div class="cs-sub">Auto-drafted by SMM AI</div>
        </div>
        <div class="cal-stat">
            <div class="cs-label">Published</div>
            <div class="cs-value">{{ $publishedCount }}</div>
            <div class="cs-sub">So far this month</div>
        </div>
        <div class="cal-stat">
            <div class="cs-label">Pending Review</div>
            <div class="cs-value">{{ $pendingReview }}</div>
            <div class="cs-sub">Drafts awaiting approval</div>
        </div>
    </div>

    {{-- ─── Main grid ─── --}}
    <div class="cal-layout">

        {{-- ─── Calendar ─── --}}
        <div class="cal-card">
            <div class="cal-nav">
                <div style="display:flex; align-items:center; gap:12px;">
                    <h2>{{ $monthName }}</h2>
                    <span class="badge badge-green">{{ $totalScheduled }} posts</span>
                </div>
                <div style="display:flex; align-items:center; gap:8px;">
                    <button class="today-btn">Today</button>
                    <div class="cal-nav-btns">
                        <button title="Previous month">&#8592;</button>
                        <button title="Next month">&#8594;</button>
                    </div>
                </div>
            </div>

            {{-- Day of week headers --}}
            <div class="cal-day-headers">
                <div class="weekend">Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div class="weekend">Sat</div>
            </div>

            {{-- Calendar Grid --}}
            <div class="cal-grid">
                {{-- Leading empty cells --}}
                @for ($e = 0; $e < $firstDay; $e++)
                    <div class="cal-day empty">
                        <span class="day-num">{{ \Carbon\Carbon::create($year, $month, 1)->subDays($firstDay - $e)->day }}</span>
                    </div>
                @endfor

                {{-- Day cells — real DB data from $monthPosts --}}
                @for ($d = 1; $d <= $daysInMonth; $d++)
                    @php
                        $dayOfWeek = (\Carbon\Carbon::create($year, $month, $d)->dayOfWeek);
                        $isWeekend = in_array($dayOfWeek, [0, 6]);
                        $isToday   = ($d === $today);
                        $dayPosts  = $monthPosts->get($d, collect());
                        $showPosts = $dayPosts->take(2);
                        $extra     = $dayPosts->count() - $showPosts->count();
                    @endphp
                    <div class="cal-day{{ $isToday ? ' today' : '' }}{{ $isWeekend ? ' weekend' : '' }}"
                         title="{{ \Carbon\Carbon::create($year, $month, $d)->format('l, F j') }}">
                        <span class="day-num">{{ $d }}</span>
                        @foreach ($showPosts as $post)
                            @php
                                [$chipCls, $dotCls] = $platformMeta[$post->platform] ?? ['chip-ai','dot-ai'];
                            @endphp
                            <div class="post-chip {{ $chipCls }}">
                                <span class="chip-dot {{ $dotCls }}"></span>
                                {{ Str::limit($post->platform_label . ': ' . $post->content, 20) }}
                            </div>
                        @endforeach
                        @if ($extra > 0)
                            <span class="more-posts">+{{ $extra }} more</span>
                        @endif
                    </div>
                @endfor

                {{-- Trailing empty cells --}}
                @php
                    $lastDayOfWeek = \Carbon\Carbon::create($year, $month, $daysInMonth)->dayOfWeek;
                    $trailing = $lastDayOfWeek < 6 ? 6 - $lastDayOfWeek : 0;
                @endphp
                @for ($t = 1; $t <= $trailing; $t++)
                    <div class="cal-day empty">
                        <span class="day-num">{{ $t }}</span>
                    </div>
                @endfor
            </div>
        </div>

        {{-- ─── Sidebar ─── --}}
        <div class="cal-sidebar">

            {{-- AI Assist Card --}}
            <div class="ai-assist-card">
                <div style="font-size:1.4rem; margin-bottom:8px;">🤖</div>
                <h3>SMM AI Scheduler</h3>
                <p>Let the AI analyse your audience engagement patterns and auto-schedule optimal posts for this month.</p>
                <button>Generate Monthly Plan</button>
            </div>

            {{-- Upcoming posts — real DB --}}
            <div class="side-card">
                <div class="side-card-header">
                    <h3>📅 Upcoming Posts</h3>
                    <span class="badge badge-blue">{{ $upcomingPosts->count() }} pending</span>
                </div>
                <div class="side-card-body" style="padding:0 18px 14px;">
                    @forelse($upcomingPosts as $post)
                        @php $d = \Carbon\Carbon::parse($post->scheduled_for)->day; @endphp
                        <div class="upcoming-post">
                            <div class="upcoming-date" style="{{ $d === $today ? 'background:#fff8f8; border:1px solid #FB4C4B;' : '' }}">
                                <span class="ud-day" style="{{ $d === $today ? 'color:#FB4C4B;' : '' }}">{{ $d }}</span>
                                <span class="ud-mon">{{ strtoupper(\Carbon\Carbon::parse($post->scheduled_for)->format('M')) }}</span>
                            </div>
                            <div class="upcoming-post-info">
                                <div class="up-title">{{ Str::limit($post->content, 40) }}</div>
                                <div class="up-meta">
                                    <span class="up-platform-badge" style="{{ $badgeStyle[$post->platform] ?? 'background:#f3f4f6;color:#555;' }}">
                                        {{ $platformLabel[$post->platform] ?? ucfirst($post->platform) }}
                                    </span>
                                    <span>{{ \Carbon\Carbon::parse($post->scheduled_for)->format('g:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center; padding:20px; color:#bbb;">
                            <div style="font-size:1.5rem; margin-bottom:6px;">📅</div>
                            <p style="font-size:.8rem; margin:0;">No upcoming posts.<br>Run the AI Scheduler to generate a plan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick legend --}}
            <div class="side-card">
                <div class="side-card-header">
                    <h3>Platform Legend</h3>
                </div>
                <div class="side-card-body">
                    @foreach (['li'=>['LinkedIn','#2563eb'],'x'=>['X (Twitter)','#111'],'ig'=>['Instagram','#ec4899'],'fb'=>['Facebook','#3b82f6'],'ai'=>['AI Drafted','#8b5cf6']] as $key => [$name, $color])
                        <div style="display:flex; align-items:center; gap:10px; padding:6px 0; border-bottom:1px solid #f5f5f5;">
                            <span style="width:10px; height:10px; border-radius:50%; background:{{ $color }}; flex-shrink:0;"></span>
                            <span style="font-size:.82rem; font-weight:600; color:#333;">{{ $name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection