@extends('admin::layouts.app')

@section('title', 'Social Analytics')

@push('styles')
<style>
    .analytics-page { display:flex; flex-direction:column; gap:24px; }

    /* Topbar */
    .an-topbar {
        display:flex; justify-content:space-between; align-items:center;
        background:#fff; border:1px solid #f0f0f0; border-radius:18px;
        padding:20px 28px; box-shadow:0 1px 6px rgba(0,0,0,.04);
    }
    .an-topbar h1 {
        font-size:1.45rem; font-weight:800; color:#111;
        display:flex; align-items:center; gap:10px; margin:0 0 4px;
    }
    .an-topbar h1 .accent { display:inline-block; width:5px; height:24px; background:linear-gradient(180deg,#0E5E4A,#10231C); border-radius:4px; }
    .an-topbar p { font-size:.85rem; color:#888; margin:0; }

    /* Buttons */
    .btn { display:inline-flex; align-items:center; gap:6px; padding:10px 20px; border-radius:12px; font-size:.85rem; font-weight:700; cursor:pointer; border:none; transition:all .2s; }
    .btn-ghost { background:#f5f5f7; color:#444; }
    .btn-ghost:hover { background:#eee; }
    .btn-primary { background:#0E5E4A; color:#fff; box-shadow:0 4px 14px rgba(251,76,75,.25); }
    .btn-primary:hover { background:#e83b3a; transform:translateY(-1px); }

    /* Date range tabs */
    .range-tabs { display:flex; gap:4px; background:#f5f5f7; border-radius:12px; padding:4px; }
    .range-tab { padding:7px 16px; border-radius:9px; font-size:.8rem; font-weight:700; cursor:pointer; color:#777; border:none; background:transparent; transition:all .2s; }
    .range-tab.active { background:#fff; color:#111; box-shadow:0 1px 4px rgba(0,0,0,.1); }

    /* KPI row */
    .kpi-row { display:grid; grid-template-columns:repeat(5,1fr); gap:14px; }
    .kpi-card {
        background:#fff; border:1px solid #f0f0f0; border-radius:14px;
        padding:18px 16px; box-shadow:0 1px 5px rgba(0,0,0,.03);
        position:relative; overflow:hidden; transition:all .25s;
    }
    .kpi-card:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,0,0,.07); }
    .kpi-card .kc-icon { font-size:1.3rem; margin-bottom:10px; }
    .kpi-card .kc-label { font-size:.68rem; font-weight:800; color:#bbb; text-transform:uppercase; letter-spacing:.06em; }
    .kpi-card .kc-val   { font-size:1.6rem; font-weight:800; color:#111; margin:4px 0 2px; line-height:1; }
    .kpi-card .kc-sub   { font-size:.72rem; color:#999; }
    .kpi-card .kc-badge { position:absolute; top:14px; right:14px; font-size:.68rem; font-weight:800; padding:3px 8px; border-radius:7px; }
    .badge-up   { background:#dcfce7; color:#15803d; }
    .badge-down { background:#fee2e2; color:#b91c1c; }
    .badge-neutral { background:#f3f4f6; color:#555; }

    /* Cards */
    .card { background:#fff; border:1px solid #f0f0f0; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,.04); overflow:hidden; }
    .card-header { padding:18px 22px 14px; border-bottom:1px solid #f5f5f5; display:flex; justify-content:space-between; align-items:center; }
    .card-header h3 { font-size:.95rem; font-weight:800; color:#111; margin:0; }
    .card-body { padding:20px 22px; }

    /* Grids */
    .charts-row { display:grid; grid-template-columns:2fr 1fr; gap:20px; }
    .bottom-row  { display:grid; grid-template-columns:1fr 1fr; gap:20px; }

    /* Chart height */
    .chart-h-lg { position:relative; height:260px; }
    .chart-h-md { position:relative; height:220px; }

    /* Platform detail table */
    .pf-table { width:100%; border-collapse:collapse; }
    .pf-table th { font-size:.7rem; font-weight:800; color:#bbb; text-transform:uppercase; letter-spacing:.06em; padding:8px 10px; text-align:left; border-bottom:2px solid #f0f0f0; }
    .pf-table td { padding:10px; font-size:.82rem; border-bottom:1px solid #f7f7f7; }
    .pf-table tr:last-child td { border-bottom:none; }
    .pf-table .pf-name { font-weight:700; color:#222; display:flex; align-items:center; gap:8px; }
    .pf-icon { width:28px; height:28px; border-radius:7px; display:inline-flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:800; }
    .pf-pos { color:#15803d; font-weight:700; }
    .pf-neg { color:#b91c1c; font-weight:700; }

    /* Top posts */
    .top-post { display:flex; gap:12px; align-items:flex-start; padding:11px 0; border-bottom:1px solid #f5f5f5; }
    .top-post:last-child { border-bottom:none; padding-bottom:0; }
    .tp-rank { width:26px; height:26px; border-radius:8px; background:#f5f5f7; display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:800; color:#555; flex-shrink:0; }
    .tp-rank.gold   { background:#fef9c3; color:#a16207; }
    .tp-rank.silver { background:#f1f5f9; color:#475569; }
    .tp-rank.bronze { background:#fef3c7; color:#92400e; }
    .tp-info { flex:1; min-width:0; }
    .tp-title { font-size:.83rem; font-weight:700; color:#222; }
    .tp-meta  { font-size:.72rem; color:#aaa; margin-top:2px; display:flex; gap:8px; flex-wrap:wrap; }
    .tp-stat  { font-size:.8rem; font-weight:800; color:#111; text-align:right; white-space:nowrap; }
    .tp-stat small { display:block; font-size:.68rem; color:#aaa; font-weight:600; }

    /* Audience geo */
    .geo-item { display:flex; align-items:center; gap:10px; padding:8px 0; border-bottom:1px solid #f5f5f5; }
    .geo-item:last-child { border-bottom:none; padding-bottom:0; }
    .geo-flag { font-size:1.1rem; width:28px; text-align:center; }
    .geo-name { font-size:.82rem; font-weight:700; color:#222; flex:1; }
    .geo-bar-wrap { width:100px; height:6px; background:#f3f4f6; border-radius:6px; overflow:hidden; }
    .geo-bar { height:100%; border-radius:6px; background:#10231C; }
    .geo-pct { font-size:.8rem; font-weight:800; color:#111; width:36px; text-align:right; }
</style>
@endpush

@section('content')
<div class="analytics-page">

    {{-- Topbar --}}
    <div class="an-topbar">
        <div>
            <h1><span class="accent"></span> Social Analytics</h1>
            <p>Cross-platform performance insights generated by the SMM AI Agent.</p>
        </div>
        <div style="display:flex; gap:12px; align-items:center;">
            <div class="range-tabs">
                <button class="range-tab">7D</button>
                <button class="range-tab active">30D</button>
                <button class="range-tab">90D</button>
                <button class="range-tab">1Y</button>
            </div>
            <button class="btn btn-ghost">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export PDF
            </button>
            <button class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Refresh Data
            </button>
        </div>
    </div>

    {{-- KPI Row --}}
    <div class="kpi-row">
        <div class="kpi-card">
            <div class="kc-icon">👁️</div>
            <span class="kc-badge badge-up">Live</span>
            <div class="kc-label">Total Impressions</div>
            <div class="kc-val">{{ number_format($totalImpressions) }}</div>
            <div class="kc-sub">Across all platforms</div>
        </div>
        <div class="kpi-card">
            <div class="kc-icon">❤️</div>
            <span class="kc-badge badge-up">Live</span>
            <div class="kc-label">Total Engagements</div>
            <div class="kc-val">{{ number_format($totalEngagements) }}</div>
            <div class="kc-sub">Likes, comments, shares</div>
        </div>
        <div class="kpi-card">
            <div class="kc-icon">⚡</div>
            <span class="kc-badge badge-neutral">Live</span>
            <div class="kc-label">Engagement Rate</div>
            <div class="kc-val">{{ round($avgEngagementRate, 2) }}%</div>
            <div class="kc-sub">Avg. across all posts</div>
        </div>
        <div class="kpi-card">
            <div class="kc-icon">👥</div>
            <span class="kc-badge badge-neutral">N/A</span>
            <div class="kc-label">New Followers</div>
            <div class="kc-val">{{ number_format($newFollowers) }}</div>
            <div class="kc-sub">No followers data yet</div>
        </div>
        <div class="kpi-card">
            <div class="kc-icon">🔥</div>
            <span class="kc-badge badge-up">Live</span>
            <div class="kc-label">Leads Identified</div>
            <div class="kc-val">{{ $leadsIdentified }}</div>
            <div class="kc-sub">By SMM AI Agent</div>
        </div>
    </div>

    {{-- Charts row --}}
    <div class="charts-row">
        {{-- Trend chart --}}
        <div class="card">
            <div class="card-header">
                <h3>📈 Impressions & Engagement Over Time</h3>
                <div style="display:flex; gap:14px; align-items:center;">
                    <span style="display:flex;align-items:center;gap:5px;font-size:.75rem;font-weight:700;color:#555;">
                        <span style="width:12px;height:3px;background:#10231C;border-radius:2px;display:inline-block;"></span>Impressions
                    </span>
                    <span style="display:flex;align-items:center;gap:5px;font-size:.75rem;font-weight:700;color:#555;">
                        <span style="width:12px;height:3px;background:#0E5E4A;border-radius:2px;display:inline-block;"></span>Engagement
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-h-lg"><canvas id="trendChart"></canvas></div>
            </div>
        </div>

        {{-- Audience distribution --}}
        <div class="card">
            <div class="card-header"><h3>Audience Split</h3></div>
            <div class="card-body">
                <div class="chart-h-md"><canvas id="audienceChart"></canvas></div>
            </div>
        </div>
    </div>

    {{-- Platform table + Top posts --}}
    <div class="bottom-row">
        {{-- Platform breakdown --}}
        <div class="card">
            <div class="card-header"><h3>📊 Platform Performance</h3></div>
            <div class="card-body" style="padding:0 22px 16px;">
                <table class="pf-table">
                    <thead>
                        <tr>
                            <th>Platform</th>
                            <th>Impressions</th>
                            <th>Engagements</th>
                            <th>Eng. Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($platforms as $key => $p)
                        <tr>
                            <td>
                                <div class="pf-name">
                                    <span class="pf-icon" style="background:{{ $p['bg'] }};color:{{ $p['fg'] }};">{{ $p['icon'] }}</span>
                                    {{ $p['label'] }}
                                </div>
                            </td>
                            <td>{{ number_format($p['impressions']) }}</td>
                            <td>{{ number_format($p['engagements']) }}</td>
                            <td class="{{ $p['engagement_rate'] > 0 ? 'pf-pos' : '' }}">{{ $p['engagement_rate'] }}%</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;padding:24px;color:#bbb;">📊 No analytics data recorded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top posts + Geo --}}
        <div style="display:flex; flex-direction:column; gap:20px;">
            {{-- Top posts --}}
            <div class="card">
                <div class="card-header"><h3>🏆 Top Performing Posts</h3></div>
                <div class="card-body" style="padding:10px 20px;">
                    @foreach([
                        ['rank'=>'gold','title'=>'AI Workforce: The future of dev agencies','platform'=>'LinkedIn','time'=>'Aug 7','impressions'=>'18.2K','eng'=>'4.8%'],
                        ['rank'=>'silver','title'=>'Behind the scenes: Building AI BDM agents','platform'=>'Instagram','time'=>'Aug 3','impressions'=>'9.4K','eng'=>'6.2%'],
                        ['rank'=>'bronze','title'=>'Thread: How we automated lead gen with AI','platform'=>'X','time'=>'Aug 5','impressions'=>'7.1K','eng'=>'3.9%'],
                    ] as $post)
                    <div class="top-post">
                        <div class="tp-rank {{ $post['rank'] }}">{{ ['gold'=>'🥇','silver'=>'🥈','bronze'=>'🥉'][$post['rank']] }}</div>
                        <div class="tp-info">
                            <div class="tp-title">{{ $post['title'] }}</div>
                            <div class="tp-meta">
                                <span>{{ $post['platform'] }}</span>
                                <span>{{ $post['time'] }}</span>
                            </div>
                        </div>
                        <div class="tp-stat">
                            {{ $post['impressions'] }}
                            <small>{{ $post['eng'] }} eng.</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Geo --}}
            <div class="card">
                <div class="card-header"><h3>🌍 Audience Geography</h3></div>
                <div class="card-body" style="padding:10px 20px;">
                    @foreach([
                        ['flag'=>'🇬🇧','country'=>'United Kingdom','pct'=>38],
                        ['flag'=>'🇺🇸','country'=>'United States','pct'=>24],
                        ['flag'=>'🇳🇬','country'=>'Nigeria','pct'=>14],
                        ['flag'=>'🇨🇦','country'=>'Canada','pct'=>10],
                        ['flag'=>'🇮🇳','country'=>'India','pct'=>8],
                    ] as $geo)
                    <div class="geo-item">
                        <span class="geo-flag">{{ $geo['flag'] }}</span>
                        <span class="geo-name">{{ $geo['country'] }}</span>
                        <div class="geo-bar-wrap"><div class="geo-bar" style="width:{{ $geo['pct'] }}%;"></div></div>
                        <span class="geo-pct">{{ $geo['pct'] }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const trendLabels      = @json($trendLabels);
    const trendImpressions = @json($trendImpressions);
    const trendEngagements = @json($trendEngagements);

    new Chart(document.getElementById('trendChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: trendLabels.length ? trendLabels : ['No data'],
            datasets: [
                { label:'Impressions', data: trendImpressions, borderColor:'#10231C', borderWidth:3, fill:true, backgroundColor:'rgba(25,28,71,0.05)', tension:0.4, pointRadius:4, pointBackgroundColor:'#10231C', yAxisID:'y' },
                { label:'Engagements', data: trendEngagements, borderColor:'#0E5E4A', borderWidth:2.5, fill:false, tension:0.4, pointRadius:4, pointBackgroundColor:'#0E5E4A', borderDash:[4,3], yAxisID:'y1' }
            ]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins: { legend:{ display:false } },
            scales: {
                y:  { grid:{ color:'#f5f5f5' }, ticks:{ font:{size:11} }, position:'left' },
                y1: { grid:{ drawOnChartArea:false }, ticks:{ font:{size:11} }, position:'right' },
                x:  { grid:{ display:false }, ticks:{ font:{size:11} } }
            }
        }
    });

    const platformData = @json($platforms);
    const pfLabels = Object.values(platformData).map(p => p.label);
    const pfValues = Object.values(platformData).map(p => p.impressions);
    new Chart(document.getElementById('audienceChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: pfLabels,
            datasets: [{ data: pfValues, backgroundColor:['#1d4ed8','#111','#ec4899','#3b82f6'], borderWidth:3, borderColor:'#fff' }]
        },
        options: {
            responsive:true, maintainAspectRatio:false, cutout:'65%',
            plugins: { legend:{ position:'bottom', labels:{ font:{size:11,weight:'700'}, padding:10 } } }
        }
    });

    document.querySelectorAll('.range-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.range-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
</script>
@endpush