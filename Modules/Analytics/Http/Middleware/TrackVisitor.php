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
        $response = $next($request);

        if (
            $request->isMethod('GET') &&
            !$request->is('admin/*') &&
            !$request->is('super-admin/*') &&
            !$request->is('dashboard/*') &&
            !$request->is('api/*') &&
            !$request->is('_debugbar/*') &&
            !$request->is('storage/*')
        ) {
            app()->terminating(function () use ($request) {
                try {
                    $agent = new Agent();

                    if ($agent->isRobot()) {
                        return;
                    }

                    $ip = $request->ip();
                    $isLocalIp = in_array($ip, ['127.0.0.1', '::1'], true);

                    $position = null;
                    if (!$isLocalIp) {
                        $position = \Illuminate\Support\Facades\Cache::remember("geoip.{$ip}", 86400, function () use ($ip) {
                            return Location::get($ip) ?: false;
                        }) ?: null;
                    }

                    $device = $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop');

                    $lastVisit = Visitor::where('ip_address', $ip)
                        ->where('user_agent', $request->userAgent())
                        ->latest('visited_at')
                        ->first();

                    if ($lastVisit) {
                        $minutes = $lastVisit->visited_at->diffInMinutes(now());
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
                } catch (\Exception $e) {
                    Log::error('Visitor tracking failed: ' . $e->getMessage());
                }
            });
        }

        return $response;
    }
}
