# Laravel Stauth
[![Latest Stable Version](https://poser.pugx.org/stauth/laravel-stauth/version)](https://packagist.org/packages/stauth/laravel-stauth)
[![License](https://poser.pugx.org/stauth/laravel-stauth/license)](https://packagist.org/packages/stauth/laravel-stauth)
[![composer.lock available](https://poser.pugx.org/stauth/laravel-stauth/composerlock)](https://packagist.org/packages/stauth/laravel-stauth)

Staging server athorization package, alternative for .htaccess, register at [stauth.io](https://www.stauth.io/)


## Installation


```bash
composer require stauth/laravel-stauth
```

Add Laravel Stauth service provider to `providers` array in `config/app.php`.

```php
<?php

'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
	
	...
        
        /**
         * Staging access control
         */
        Stauth\StauthServiceProvider::class,        
        ...

],
?>
```

Add Stauth middleware in `app/Http/Kernel.php`, it is **very important** that `StauthProtection` is **above** any response cache extension middleware like laravel-responsecache:

```php

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
   
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class, 
    ...
    
    
            \Stauth\Middleware\StauthProtection::class,
    
    
    ...
            \Spatie\ResponseCache\Middlewares\CacheResponse::class,
```

Generate access token at [stauth.io](https://www.stauth.io) and add it as a `STAUTH_ACCESS_TOKEN` param in `.env` file:

```bash
BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

...

STAUTH_ACCESS_TOKEN=verylongchainoflettersmixedwithsomerandomnumbers123

```

By default protected environment is `staging`, in order to change this, add `STAUTH_PROTECTED_ENV` param in `.env` file: 

```bash
BROADCAST_DRIVER=log
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

...

STAUTH_PROTECTED_ENV=local

```

## Configuration

You can also publish configuration and update required params in php file:

```bash

php artisan vendor:publish --provider="Stauth\StauthServiceProvider" --tag=config

```

## Cache

Please keep in mind that this package takes adventage of `csrf_token`, therefore it is important to exclude both routes `/stauth/protected` and `/stauth/authorize` from any response caching engines.
