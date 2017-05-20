<?php
namespace Stauth\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class StauthProtection
{
    /**
     * @var array
     */
    protected $whiteListedRoutes = [
        'stauth-authorization',
        'stauth-protection',

    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (App::environment() !== Config::get('app.protected_env'))
            return $next($request);

        if ( in_array($request->route()->getName(), $this->whiteListedRoutes))
            return $next($request);

        if ( Session::get('stauth-authorized'))
            return $next($request);

        return redirect()->route('stauth-protection');
    }
}