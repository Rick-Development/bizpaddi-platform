@extends('admin::layouts.app')

@section('title', 'Social Media Dashboard')

@push('styles')
<style>
    .smm-page { display: flex; flex-direction: column; gap: 24px; }
    .smm-topbar { display:flex; justify-content:space-between; align-items:center; background:#fff; border:1px solid #f0f0f0; border-radius:18px; padding:20px 28px; box-shadow:0 1px 6px rgba(0,0,0,.04); }
    .smm-topbar h1 { font-size:1.45rem; font-weight:800; color:#111; display:flex; align-items:center; gap:10px; margin:0 0 4px; }
    .smm-topbar h1 .accent { display:inline-block; width:5px; height:24px; background:linear-gradient(180deg,#0E5E4A,#10231C); border-radius:4px; }
    .smm-topbar p { font-size:.85rem; color:#888; margin:0; }
    .btn { display:inline-flex; align-items:center; gap:6px; padding:10px 20px; border-radius:12px; font-size:.85rem; font-weight:700; cursor:pointer; border:none; transition:all .2s; text-decoration:none; }
    .btn-ghost { background:#f5f5f7; color:#444; }
    .btn-ghost:hover { background:#eee; }
    .btn-primary { background:#0E5E4A; color:#fff; box-shadow:0 4px 14px rgba(251,76,75,.25); }
    .btn-primary:hover { background:#e83b3a; transform:translateY(-1px); }
    .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
    .stat-card { background:#fff; border:1px solid #f0f0f0; border-radius:16px; padding:22px 20px; box-shadow:0 1px 6px rgba(0,0,0,.04); position:relative; overflow:hidden; transition:all .25s; }
    .stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(0,0,0,.08); }
    .stat-card .sc-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; margin-bottom:14px; }
    .stat-card .sc-label { font-size:.72rem; font-weight:700; color:#bbb; text-transform:uppercase; letter-spacing:.06em; }
    .stat-card .sc-value { font-size:1.9rem; font-weight:800; color:#111; margin:5px 0 2px; line-height:1; }
    .stat-card .sc-sub { font-size:.75rem; color:#999; }
    .stat-card .sc-badge { position:absolute; top:18px; right:18px; font-size:.72rem; font-weight:800; padding:3px 9px; border-radius:8px; }
    .badge-up { background:#dcfce7; color:#15803d; }
    .badge-neutral { background:#f3f4f6; color:#555; }
    .smm-grid-main { display:grid; grid-template-columns:2fr 1fr; gap:20px; }
    .card { background:#fff; border:1px solid #f0f0f0; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,.04); overflow:hidden; }
    .card-header { padding:18px 22px 14px; border-bottom:1px solid #f5f5f5; display:flex; justify-content:space-between; align-items:center; }
    .card-header h3 { font-size:.95rem; font-weight:800; color:#111; margin:0; }
    .card-body { padding:20px 22px; }
    .chart-wrap { position:relative; height:240px; }
    .campaign-item { padding:12px 0; border-bottom:1px solid #f5f5f5; }
    .campaign-item:last-child { border-bottom:none; padding-bottom:0; }
    .ci-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:6px; }
    .ci-name { font-size:.85rem; font-weight:700; color:#222; }
    .ci-pct { font-size:.8rem; font-weight:800; color:#555; }
    .progress-bar { height:7px; background:#f3f4f6; border-radius:8px; overflow:hidden; }
    .progress-fill { height:100%; border-radius:8px; }
    .feed-item { display:flex; gap:12px; align-items:flex-start; padding:12px 0; border-bottom:1px solid #f5f5f5; }
    .feed-item:last-child { border-bottom:none; padding-bottom:0; }
    .feed-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.85rem; font-weight:800; flex-shrink:0; }
    .feed-info .fi-title { font-size:.85rem; font-weight:700; color:#222; }
    .feed-info .fi-sub { font-size:.75rem; color:#aaa; margin-top:2px; }
    .feed-info .fi-text { font-size:.8rem; color:#555; margin-top:4px; display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:2; overflow:hidden; }
    .log-item { display:flex; justify-content:space-between; align-items:center; padding:9px 0; border-bottom:1px solid #f5f5f5; font-size:.8rem; font-weight:600; }
    .log-item:last-child { border-bottom:none; padding-bottom:0; }
    .log-dot { display:inline-block; width:8px; height:8px; border-radius:50%; margin-right:7px; }
    .log-time { color:#bbb; font-size:.72rem; white-space:nowrap; }
    .platform-row { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #f5f5f5; }
    .platform-row:last-child { border-bottom:none; padding-bottom:0; }
    .pr-icon { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.8rem; font-weight:800; flex-shrink:0; }
    .pr-name { font-size:.83rem; font-weight:700; color:#222; flex:1; }
    .pr-val { font-size:.85rem; font-weight:800; color:#111; }
    .hero-strip { background:linear-gradient(135deg,#10231C 0%,#2d3185 60%,#3a1c47 100%); border-radius:18px; padding:28px 30px; color:#fff; position:relative; overflow:hidden; display:flex; justify-content:space-between; align-items:center; }
    .hero-strip::before { content:''; position:absolute; top:-40px; right:-40px; width:200px; height:200px; background:rgba(251,76,75,.12); border-radius:50%; }
    .hero-strip::after  { content:''; position:absolute; bottom:-60px; right:80px; width:150px; height:150px; background:rgba(255,255,255,.05); border-radius:50%; }
    .hero-strip h2 { font-size:1.1rem; font-weight:800; margin:0 0 6px; }
    .hero-strip p { font-size:.82rem; color:rgba(255,255,255,.65); margin:0; max-width:380px; line-height:1.6; }
    .hero-cta { display:flex; gap:10px; margin-top:16px; flex-wrap:wrap; }
    .hero-stat { text-align:center; }
    .hero-stat .hs-num { font-size:1.6rem; font-weight:800; line-height:1; }
    .hero-stat .hs-lbl { font-size:.72rem; color:rgba(255,255,255,.55); margin-top:3px; }
    .hero-stats { display:flex; gap:32px; position:relative; z-index:1; }
    .hero-left { position:relative; z-index:1; }
    .btn-light { background:rgba(255,255,255,.15); color:#fff; border:1px solid rgba(255,255,255,.2); font-size:.8rem; padding:8px 16px; }
    .btn-light:hover { background:rgba(255,255,255,.25); }
    .btn-red { background:#0E5E4A; color:#fff; font-size:.8rem; padding:8px 16px; }
    .btn-red:hover { background:#e83b3a; }
    .empty-state { text-align:center; padding:30px 20px; color:#bbb; }
    .empty-state .es-icon { font-size:2rem; margin-bottom:8px; }
    .empty-state p { font-size:.82rem; margin:0; }
</style>
@endpush

@section('content')
<div class="smm-page">

    {{-- Topbar --}}
    <div class="smm-topbar">
        <div>
            <h1><span class="accent"></span> Social Media Command</h1>
            <p>Autonomous growth, strategy, and community engagement hub.</p>
        </div>
        <div style="display:flex; gap:10px;">
            <button class="btn btn-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Sync Platforms
            </button>
            <button class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Run SMM AI Agent
            </button>
        </div>
    </div>

    {{-- Hero --}}
    <div class="hero-strip">
        <div class="hero-left">
            <h2>🤖 SMM AI Agent — Active</h2>
            <p>Your autonomous Social Media Manager is monitoring platforms, identifying leads, scheduling content, and engaging with your audience 24/7.</p>
            <div class="hero-cta">
                <a href="{{ route('admin.social-media.inbox') }}" class="btn btn-light">View Inbox</a>
                <a href="{{ route('admin.social-media.calendar') }}" class="btn btn-light">Open Calendar</a>
                <a href="{{ route('admin.social-media.analytics') }}" class="btn btn-red">Analytics</a>
            </div>
        </div>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hs-num">{{ number_format($totalImpressions) }}</div>
                <div class="hs-lbl">Total Impressions</div>
            </div>
            <div class="hero-stat">
                <div class="hs-num">{{ $totalLeads }}</div>
                <div class="hs-lbl">Leads Found</div>
            </div>
            <div class="hero-stat">
                <div class="hs-num">{{ $engagementRate }}%</div>
                <div class="hs-lbl">Engagement</div>
            </div>
            <div class="hero-stat">
                <div class="hs-num">{{ $scheduledPosts }}</div>
                <div class="hs-lbl">Queued Posts</div>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="sc-icon" style="background:#fff0f0;">📣</div>
            <span class="sc-badge badge-up">Live</span>
            <div class="sc-label">Total Impressions</div>
            <div class="sc-value">{{ number_format($totalImpressions) }}</div>
            <div class="sc-sub">Across all platforms</div>
        </div>
        <div class="stat-card">
            <div class="sc-icon" style="background:#f0fdf4;">🔥</div>
            <span class="sc-badge badge-up">+{{ $leadsThisMonth }} this month</span>
            <div class="sc-label">Social Leads</div>
            <div class="sc-value">{{ $totalLeads }}</div>
            <div class="sc-sub">Identified & pushed to BDM</div>
        </div>
        <div class="stat-card">
            <div class="sc-icon" style="background:#eff6ff;">📅</div>
            <span class="sc-badge badge-neutral">{{ $activeCampaigns }} campaigns</span>
            <div class="sc-label">Scheduled Posts</div>
            <div class="sc-value">{{ $scheduledPosts }}</div>
            <div class="sc-sub">Queued in content calendar</div>
        </div>
        <div class="stat-card">
            <div class="sc-icon" style="background:#fefce8;">⚡</div>
            <span class="sc-badge badge-up">Live</span>
            <div class="sc-label">Avg. Engagement</div>
            <div class="sc-value">{{ $engagementRate }}%</div>
            <div class="sc-sub">Across all tracked posts</div>
        </div>
    </div>

    {{-- Main grid --}}
    <div class="smm-grid-main">
        {{-- Reach Chart --}}
        <div class="card">
            <div class="card-header">
                <h3>📈 Reach Trends</h3>
                <span style="font-size:.78rem; color:#aaa; font-weight:600;">Last 7 months</span>
            </div>
            <div class="card-body">
                <div class="chart-wrap">
                    <canvas id="reachChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Platform breakdown --}}
        <div class="card">
            <div class="card-header"><h3>Platform Breakdown</h3></div>
            <div class="card-body" style="padding:14px 20px;">
                @php
                    $pfMeta = [
                        'linkedin'  => ['in', '#dbeafe', '#1d4ed8'],
                        'x'         => ['X',  '#f3f4f6', '#111'],
                        'instagram' => ['ig', '#fce7f3', '#be185d'],
                        'facebook'  => ['fb', '#eff6ff', '#1d4ed8'],
                    ];
                @endphp
                @forelse($platformStats as $key => $stat)
                @php [$icon, $bg, $fg] = $pfMeta[$key] ?? ['?','#f5f5f5','#333']; @endphp
                <div class="platform-row">
                    <div class="pr-icon" style="background:{{ $bg }};color:{{ $fg }};">{{ $icon }}</div>
                    <span class="pr-name">{{ ucfirst($key) }}</span>
                    <span class="pr-val">{{ number_format($stat['impressions']) }}</span>
                </div>
                @empty
                <div class="empty-state">
                    <div class="es-icon">📊</div>
                    <p>No platform data yet.<br>Run the SMM AI Agent to begin tracking.</p>
                </div>
                @endforelse
                <div style="height:140px; margin-top:14px;">
                    <canvas id="platformChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom row --}}
    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px;">

        {{-- Campaigns --}}
        <div class="card">
            <div class="card-header">
                <h3>🎯 Active Campaigns</h3>
                <span style="font-size:.72rem; font-weight:700; background:#ede9fe; color:#6d28d9; padding:3px 9px; border-radius:7px;">{{ $activeCampaigns }} running</span>
            </div>
            <div class="card-body">
                @forelse($campaigns as $campaign)
                <div class="campaign-item">
                    <div class="ci-header">
                        <span class="ci-name">{{ $campaign->name ?? 'Campaign #' . $campaign->id }}</span>
                        <span class="ci-pct">{{ $campaign->progress }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width:{{ $campaign->progress }}%; background:#0E5E4A;"></div>
                    </div>
                    @if($campaign->platforms)
                    <div style="font-size:.72rem; color:#aaa; margin-top:4px;">{{ implode(' · ', array_map('ucfirst', $campaign->platforms)) }}</div>
                    @endif
                </div>
                @empty
                <div class="empty-state">
                    <div class="es-icon">🎯</div>
                    <p>No campaigns yet.<br>AI Agent will create them automatically.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Recent Posts --}}
        <div class="card">
            <div class="card-header"><h3>📰 Recent Posts</h3></div>
            <div class="card-body" style="padding:10px 20px;">
                @php
                    $pfIcons = ['linkedin'=>['in','#dbeafe','#1d4ed8'],'x'=>['X','#f3f4f6','#111'],'instagram'=>['ig','#fce7f3','#be185d'],'facebook'=>['fb','#eff6ff','#1d4ed8']];
                @endphp
                @forelse($recentPosts as $post)
                @php [$icon,$bg,$fg] = $pfIcons[$post->platform] ?? ['?','#f5f5f5','#333']; @endphp
                <div class="feed-item">
                    <div class="feed-icon" style="background:{{ $bg }};color:{{ $fg }};">{{ $icon }}</div>
                    <div class="feed-info">
                        <div class="fi-title">{{ $post->platform_label }}</div>
                        <div class="fi-sub">{{ $post->created_at->diffForHumans() }} · {{ $post->status }}</div>
                        <div class="fi-text">{{ $post->content }}</div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <div class="es-icon">📰</div>
                    <p>No posts yet. The AI Agent will start scheduling content soon.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- AI Agent Logs --}}
        <div class="card">
            <div class="card-header">
                <h3>🤖 SMM AI Agent Logs</h3>
                <span style="display:inline-flex;align-items:center;gap:5px;font-size:.72rem;font-weight:700;color:#15803d;">
                    <span style="width:7px;height:7px;border-radius:50%;background:#22c55e;display:inline-block;"></span>Live
                </span>
            </div>
            <div class="card-body" style="padding:10px 20px;">
                @forelse($agentLogs as $log)
                @php
                    $sentimentColor = match($log->sentiment) { 'positive'=>'#22c55e', 'negative'=>'#0E5E4A', default=>'#3b82f6' };
                @endphp
                <div class="log-item">
                    <span>
                        <span class="log-dot" style="background:{{ $sentimentColor }};"></span>
                        [{{ $log->platform_label }}] {{ Str::limit($log->sender_name . ': ' . $log->message, 52) }}
                    </span>
                    <span class="log-time">{{ $log->created_at->diffForHumans(null, true) }}</span>
                </div>
                @empty
                <div class="empty-state">
                    <div class="es-icon">🤖</div>
                    <p>No activity logged yet.<br>Agent logs will appear here in real-time.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const trendLabels = @json($trendLabels);
    const trendData   = @json($trendData);

    new Chart(document.getElementById('reachChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Impressions',
                data: trendData,
                borderColor: '#10231C', borderWidth: 3,
                fill: true, backgroundColor: 'rgba(25,28,71,0.06)',
                tension: 0.4, pointRadius: 4, pointBackgroundColor: '#10231C',
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#f5f5f5' }, ticks: { font: { size: 11 } } },
                x: { grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });

    const platformStats = @json($platformStats);
    const labels = Object.keys(platformStats).map(k => k.charAt(0).toUpperCase() + k.slice(1));
    const values = Object.values(platformStats).map(s => s.impressions);
    new Chart(document.getElementById('platformChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{ data: values, backgroundColor: ['#1d4ed8','#111','#ec4899','#3b82f6'], borderWidth: 2, borderColor: '#fff' }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '68%',
            plugins: { legend: { position: 'bottom', labels: { font: { size: 11, weight: '700' }, padding: 10 } } }
        }
    });
});
</script>
@endpush