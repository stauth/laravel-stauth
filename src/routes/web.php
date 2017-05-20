<?php

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;


Route::group(['middleware' => ['web']], function () {

    Route::get('/stauth/protected', function () {
        return view('stauth::home');
    })->name('stauth-protection');

    Route::post('/stauth/authorize', function () {

        $jwt = Input::get('token');
        $client = new Client([
            'defaults' => [
                'headers' => [
                    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjA5Zjk5MWY1MWI5ZmZhZGM2MjM5NDA5M2YyMTljMmZlNDk2ZDZhYzNlOTExMjNjYjAwZDM1MjQ4YTAwMmRjOTQyNjFjZGE5YTE2ZmI3ZDg1In0.eyJhdWQiOiIxIiwianRpIjoiMDlmOTkxZjUxYjlmZmFkYzYyMzk0MDkzZjIxOWMyZmU0OTZkNmFjM2U5MTEyM2NiMDBkMzUyNDhhMDAyZGM5NDI2MWNkYTlhMTZmYjdkODUiLCJpYXQiOjE0OTQwMTU5MzgsIm5iZiI6MTQ5NDAxNTkzOCwiZXhwIjoxNTI1NTUxOTM4LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.p5H3CNWH3QzZSQGwQuHbOvG9iROZRlQhYPNi7MK5y6roPBQXKvmZ3fJouGDEufu2tRqAlIPPalpF-4yOf_7trNZabvF-jCgYqaZMnJOGJmIdW605sjmZLdWbxei6RJd2cgpFYxW2EH4DYqAxC9B6p0PyJtVR5Nu6DOZgdYd_SFmBmDxmeZMM86bIg-YxQS-W1bKAQeFAdp8OpMxMnlKIDmxQNnbN2fxzmrYJ2UUp6nqIGvWgRq9IlcsGxg0JAumyk736nRr8iwGcYiKdmkc6LAIZsrzFxcJGiD0eyQWd7G0Hy5OQtp4U-DQzV9QDmH2_DeHEhtyLdAPP7tKZBYOUg-2LtEigqqD2cgSYg3r-ykUfN0dEerzr4IFJE5zXF0LTBR5xsTfcnEFfnqUKzkr0_21RHlSufeTdogH8k_FqqYBZHuzJtQglvlzWiaxdtpIDYfGPTBOGr9LlLD4yVxhx_HfMhruCTQT_PSUshe5OIpRiy-UR9jwOkCJEbz2J31fVoBBsdQ8ElmIMS20o___jyb6TSdte3wULaysmcWQRDqrY93XXp87-KMsNpXb2lHNQdFvlt3wfx1m7QMClP11bkHCsXMdWVP8efY1OvFUDYL7zZZIjUdyR1t-8dhBaZkfSJcMkYeIOYn1jjTv-VAPw_YqZnRe_Uq1ny-_QIkN-PZs'
                ],
                'query' => [
                    'token' => $jwt,
                ]
            ]
        ]);

        return $client->post('http://stauth.dev/api/authorize', [
            'timeout' => 15,
            'debug' => true
        ]);

        Session::put('stauth-authorized', 1);
        Session::save();

    })->name('stauth-authorization');
});