@extends('admin::layouts.app')

@section('title', 'Visitor Session Details')

@section('content')
<div class="visitor-detail-page">
    <!-- Header -->
    <div class="page-header">
        <div class="header-content">
            <div>
                <a href="{{ route('admin.visitors.index') }}" class="back-link">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Sessions
                </a>
                <h1 class="page-title">Session Details</h1>
            </div>
            <form method="POST" action="{{ route('admin.visitors.destroy', $visitor->id) }}" onsubmit="return confirm('Delete this visitor session?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete-main">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Session
                </button>
            </form>
        </div>
    </div>

    <div class="content-grid">
        <!-- Main Info Card -->
        <div class="info-card">
            <h2 class="card-title">Session Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>IP Address</label>
                    <span class="font-mono">{{ $visitor->ip_address }}</span>
                </div>

                <div class="info-item">
                    <label>Session ID</label>
                    <span class="font-mono text-sm">{{ $visitor->session_id ?: 'N/A' }}</span>
                </div>

                <div class="info-item">
                    <label>Visit Date</label>
                    <span>{{ $visitor->created_at->format('F d, Y') }}</span>
                </div>

                <div class="info-item">
                    <label>Visit Time</label>
                    <span>{{ $visitor->created_at->format('h:i A') }}</span>
                </div>

                <div class="info-item">
                    <label>Time Spent</label>
                    <span class="time-badge">{{ gmdate('i:s', $visitor->time_spent) }}</span>
                </div>

                <div class="info-item">
                    <label>Relative Time</label>
                    <span>{{ $visitor->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>

        <!-- Location Card -->
        <div class="info-card">
            <h2 class="card-title">
                <svg class="title-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Location Details
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Country</label>
                    <span>{{ $visitor->country ?: 'Unknown' }}</span>
                </div>

                <div class="info-item">
                    <label>State/Region</label>
                    <span>{{ $visitor->state ?: 'Unknown' }}</span>
                </div>

                <div class="info-item">
                    <label>City</label>
                    <span>{{ $visitor->city ?: 'Unknown' }}</span>
                </div>

                <div class="info-item">
                    <label>LGA</label>
                    <span>{{ $visitor->lga ?: 'Unknown' }}</span>
                </div>
            </div>
        </div>

        <!-- Device Info Card -->
        <div class="info-card">
            <h2 class="card-title">
                <svg class="title-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Device Information
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <label>Device Type</label>
                    @if($visitor->device)
                        <span class="device-badge device-{{ strtolower($visitor->device) }}">
                            {{ ucfirst($visitor->device) }}
                        </span>
                    @else
                        <span>Unknown</span>
                    @endif
                </div>

                <div class="info-item">
                    <label>Browser</label>
                    <span>{{ $visitor->browser ?: 'Unknown' }}</span>
                </div>

                <div class="info-item">
                    <label>Platform</label>
                    <span>{{ $visitor->platform ?: 'Unknown' }}</span>
                </div>

                <div class="info-item full-width">
                    <label>User Agent</label>
                    <span class="text-sm text-gray-600 break-all">{{ $visitor->user_agent }}</span>
                </div>
            </div>
        </div>

        <!-- Page Info Card -->
        <div class="info-card full-width">
            <h2 class="card-title">
                <svg class="title-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Page Information
            </h2>
            <div class="info-grid">
                <div class="info-item full-width">
                    <label>URL Visited</label>
                    <a href="{{ $visitor->url }}" target="_blank" class="url-link">
                        {{ $visitor->url }}
                        <svg class="external-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>

                @if($visitor->referer)
                <div class="info-item full-width">
                    <label>Referrer</label>
                    <a href="{{ $visitor->referer }}" target="_blank" class="url-link">
                        {{ $visitor->referer }}
                        <svg class="external-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Related Sessions -->
        @if($relatedSessions->count() > 0)
        <div class="info-card full-width">
            <h2 class="card-title">
                <svg class="title-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Related Sessions (Same IP/Session)
            </h2>
            <div class="related-sessions">
                @foreach($relatedSessions as $session)
                <a href="{{ route('admin.visitors.show', $session->id) }}" class="related-session-item">
                    <div class="session-info">
                        <span class="session-url">{{ Str::limit($session->url, 50) }}</span>
                        <span class="session-time">{{ $session->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <div class="session-meta">
                        <span class="time-badge">{{ gmdate('i:s', $session->time_spent) }}</span>
                        @if($session->device)
                            <span class="device-badge device-{{ strtolower($session->device) }}">
                                {{ ucfirst($session->device) }}
                            </span>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<style>
.visitor-detail-page {
    padding: 2rem;
    background: #f8fafc;
    min-height: 100vh;
}

.page-header {
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    flex-wrap: wrap;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #64748b;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: color 0.2s;
}

.back-link:hover {
    color: #191C47;
}

.back-link svg {
    width: 1.25rem;
    height: 1.25rem;
}

.page-title {
    font-size: 1.875rem;
    font-weight: 800;
    color: #191C47;
    margin: 0;
}

.btn-delete-main {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: #fee2e2;
    color: #dc2626;
    border: none;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-delete-main:hover {
    background: #fecaca;
}

.btn-delete-main svg {
    width: 1.25rem;
    height: 1.25rem;
}

.content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

.info-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.full-width {
    grid-column: 1 / -1;
}

.card-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #191C47;
    margin: 0 0 1.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.title-icon {
    width: 1.25rem;
    height: 1.25rem;
    color: #FB4C4B;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item label {
    font-size: 0.75rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.info-item span {
    font-size: 0.9375rem;
    color: #191C47;
    font-weight: 600;
}

.time-badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    background: #fef3c7;
    color: #92400e;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 700;
    font-family: monospace;
    width: fit-content;
}

.device-badge {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 700;
    width: fit-content;
}

.device-desktop {
    background: #e0e7ff;
    color: #4338ca;
}

.device-mobile {
    background: #fce7f3;
    color: #be123c;
}

.device-tablet {
    background: #d1fae5;
    color: #047857;
}

.url-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #2563eb;
    text-decoration: none;
    word-break: break-all;
    transition: color 0.2s;
}

.url-link:hover {
    color: #1d4ed8;
}

.external-icon {
    width: 1rem;
    height: 1rem;
    flex-shrink: 0;
}

.related-sessions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.related-session-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    text-decoration: none;
    transition: all 0.2s;
    gap: 1rem;
}

.related-session-item:hover {
    background: #f1f5f9;
    transform: translateX(4px);
}

.session-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.session-url {
    font-size: 0.9375rem;
    color: #191C47;
    font-weight: 600;
}

.session-time {
    font-size: 0.8125rem;
    color: #64748b;
}

.session-meta {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

@media (max-width: 768px) {
    .visitor-detail-page {
        padding: 1rem;
    }

    .content-grid {
        grid-template-columns: 1fr;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .related-session-item {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>
@endsection
