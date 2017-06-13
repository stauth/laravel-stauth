<?php

use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

$middleware = ['web'];
if (config('responsecache.enabled')) {
    $middleware[] = 'doNotCacheResponse';
}

Route::group(['middleware' => $middleware], function () {

    Route::get('/stauth/protected', function () {

        if($this->app->bound('debugbar')) {
            app('debugbar')->disable();
        }

        return view('stauth::home');
    })->name('stauth-protection');


    Route::post('/stauth/authorize', function () {

        app('debugbar')->disable();
        $jwt = Input::get('token');
        $client = new Client();

        try {
            $response = $client->post('https://www.stauth.io/api/authorize', [
                'headers' => [
                    'Referer' => url()->current(),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Accept' => 'application/json',
                    'Authorization' => sprintf('Bearer %s', config('stauth.access_token'))
                ],
                'query' => [
                    'token' => $jwt,
                ]
            ]);

        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
        }

        if ($response->getStatusCode() === 200) {
            Session::put('stauth-authorized', 1);
            Session::save();
        }

        return new JsonResponse([$response->getBody()->getContents()], $response->getStatusCode());

    })->name('stauth-authorization');

    Route::get('{any}', function ($any) {
        if (Session::get('stauth-authorized')) {
            abort(404);
        }

        return redirect(route('stauth-protection'));

    })->where('any', '.*');
});