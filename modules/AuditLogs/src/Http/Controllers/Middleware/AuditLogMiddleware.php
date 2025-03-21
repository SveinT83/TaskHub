<?php

namespace Modules\AuditLogs\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\AuditLogs\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check() && $request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('DELETE')) {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $request->method(),
                'model_type' => $request->route()->getController() ? get_class($request->route()->getController()) : null,
                'model_id' => $request->route('id'),
                'changes' => json_encode($request->except('_token')),
            ]);
        }

        return $response;
    }
}