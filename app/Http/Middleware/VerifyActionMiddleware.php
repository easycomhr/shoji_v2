<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\BaseService;

class VerifyActionMiddleware
{
    public function handle(Request $request, Closure $next, $action = null)
    {
        $action = $action ?? config('constant.actions.view');
        $isAllow = BaseService::verifyAction($request, $action);

        if (!$isAllow) {
            return redirect()->route('admin.error.permission_denied');
        }

        return $next($request);
    }
}
