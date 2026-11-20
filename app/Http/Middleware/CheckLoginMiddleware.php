<?php

namespace App\Http\Middleware;

use Closure;
use App\Providers\RouteServiceProvider;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Modules\System\Dashboard\Users\Models\UserInfoModel;

class CheckLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Laravel's remember cookie can restore Auth after the regular session
        // expires. Rebuild the legacy $_SESSION values used by this app.
        if (Auth::check()) {
            $user = Auth::user();

            if (empty($_SESSION['role']) || empty($_SESSION['id'])) {
                $userInfo = UserInfoModel::where('user_id', $user->id)->first();
                $_SESSION['role'] = $user->role;
                $_SESSION['id'] = $user->id;
                $_SESSION['email'] = $user->email;
                $_SESSION['name'] = $user->name;
                $_SESSION['code'] = $user->id_personnel;
                $_SESSION['color_view'] = $userInfo->color_view ?? 2;

                if (in_array($user->role, ['ADMIN', 'EMPLOYEE'], true)) {
                    $sidebarConfig = config('SidebarSystem');
                    $_SESSION['sidebar'] = $sidebarConfig[$user->role] ?? [];
                }
            }
        }

        if (Auth::check() && in_array($_SESSION['role'] ?? null, ['ADMIN', 'EMPLOYEE', 'CTV'], true)) {
            return $next($request);
        };
        return redirect()->route('login');
    }
}
