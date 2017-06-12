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
        if ( Session::get('stauth-last-url') === null) {
            Session::put('stauth-last-url', url('/'));
        }

        if (App::environment() !== Config::get('stauth.protected_env'))
            return $next($request);

        if ( in_array($request->route()->getName(), $this->whiteListedRoutes))
            return $next($request);

        if ( Session::get('stauth-authorized'))
            return $next($request);

        Session::put('stauth-last-url', $request->url());
        return redirect()->route('stauth-protection');
    }
}