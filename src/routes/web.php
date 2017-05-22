<?php

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

Route::group(['middleware' => ['web']], function () {

    Route::get('/stauth/protected', function () {
        return view('stauth::home');
    })->name('stauth-protection');

});

Route::group(['middleware' => ['stauth']], function () {

    Route::post('/stauth/authorize', function () {

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

        return $response;

    })->name('stauth-authorization');
});