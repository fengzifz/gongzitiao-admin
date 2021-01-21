<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;

class CheckIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ipAllowed = Setting::where('key_name', 'ip_allowed')
            ->get()->first();

        // 但白名单存在时，并且白名单不等于 0.0.0.0
        if ($ipAllowed->value && $ipAllowed->value != '0.0.0.0') {
            $ipAllowedArr = explode(',', $ipAllowed->value);
            $ip = $request->ip();
            foreach ($ipAllowedArr as $v) {
                if ($ip != $v) {
                    abort(403, '无访问权限');
                }
            }
        }

        return $next($request);
    }
}
