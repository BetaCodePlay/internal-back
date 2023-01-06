<?php

namespace App\Http\Middleware;

use App\Whitelabels\Enums\Status;
use Closure;

class Maintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('whitelabels.whitelabel_status') == Status::$whitelabel_dotpanel_maintenance) {
            abort(503);
        }
        return $next($request);
    }
}
