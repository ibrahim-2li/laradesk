<?php

namespace App\Http\Middleware;

use App\Models\User;
use Auth;
use Closure;
use Exception;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     * @throws Exception
     */
    public function handle($request, Closure $next)
    {
        $route = $request->route();
        $routeName = $route->getName();
        
        // Define what constitutes a protected dashboard route
        $isApiDashboard = strpos($route->uri, 'api/dashboard') !== false;
        $isWebDashboard = $routeName && (str_starts_with($routeName, 'dashboard.') || str_starts_with($routeName, 'admin.'));
        
        if ($isApiDashboard || $isWebDashboard) {
            
            $guard = $isApiDashboard ? 'sanctum' : null; // null uses default/configured guard
            
            if (!Auth::guard($guard)->check()) {
                return $request->expectsJson() 
                    ? response()->json(['message' => __('Unauthorized')], 401)
                    : redirect()->route('login');
            }

            /** @var User $user */
            $user = Auth::guard($guard)->user();
            
            // 1. Check General Dashboard Access
            if (!$user->userRole->checkDashboardAccess()) {
                return $request->expectsJson()
                    ? response()->json(['message' => __('Forbidden')], 403)
                    : abort(403, __('Unauthorized'));
            }

            // 2. Check Specific Route Permission
            $action = $route->action['controller'] ?? '';
            if ($action && is_string($action)) {
                $controllerClass = explode('@', $action)[0];
                
                // Allow Home/Dashboard main page if they have general dashboard access
                // Adjust this list as needed
                $allowedClasses = [
                    'App\Livewire\Dashboard\Home',
                ];

                if (!in_array($controllerClass, $allowedClasses)) {
                    $path = str_replace('\\', '.', $controllerClass);
                    if (!$user->userRole->checkPermission($path)) {
                        return $request->expectsJson()
                            ? response()->json(['message' => __('Forbidden')], 403)
                            : abort(403, __('Unauthorized'));
                    }
                }
            }
        }
        return $next($request);
    }
}
