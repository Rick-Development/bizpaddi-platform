<?php

namespace Modules\Analytics\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Analytics\Models\Visitor;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if (!$request->is('admin/*') && !$request->is('api/*') && !$request->is('_debugbar/*')) {
                
                $agent = new Agent();
                
                // Optional: Ignore robots/crawlers to keep stats clean
                if ($agent->isRobot()) {
                    return $next($request);
                }

                $ip = $request->ip();
                
                // Attempt to get location data
                // Handle Private IPs (Localhost/Intranet) for testing
                // If IP is private/reserved, use a public IP to simulate location
                $position = Location::get($ip);
                if (!$position && (app()->isLocal() || config('app.debug'))) {
                    $position = Location::get('8.8.8.8'); // Google Public DNS IP (US)
                }

                $device = $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop');
                
                // Calculate time spent on PREVIOUS page
                // Find the latest visit for this session/IP
                $lastVisit = Visitor::where('ip_address', $ip)
                    ->where('user_agent', $request->userAgent())
                    ->latest('visited_at')
                    ->first();

                if ($lastVisit) {
                    $minutes = $lastVisit->visited_at->diffInMinutes(now());
                    // Only update if within a reasonable session timeout (e.g., 30 mins)
                    if ($minutes < 30) {
                        $lastVisit->update(['time_spent' => $lastVisit->visited_at->diffInSeconds(now())]);
                    }
                }

                Visitor::create([
                    'ip_address' => $ip,
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'referer' => $request->header('referer'),
                    'user_id' => auth()->id(),
                    'visited_at' => now(),
                    'country' => $position ? $position->countryName : null,
                    'city' => $position ? $position->cityName : null,
                    'state' => $position ? $position->regionName : null,
                    'browser' => $agent->browser(),
                    'device' => $device,
                    'platform' => $agent->platform(),
                ]);
            }
        } catch (\Exception $e) {
            // Fail silently to not impact user experience
            Log::error('Visitor tracking failed: ' . $e->getMessage());
        }

        return $next($request);
    }
}
