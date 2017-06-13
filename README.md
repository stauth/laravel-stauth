# Laravel Stauth
[![Latest Stable Version](https://poser.pugx.org/stauth/laravel-stauth/version)](https://packagist.org/packages/stauth/laravel-stauth)
[![License](https://poser.pugx.org/stauth/laravel-stauth/license)](https://packagist.org/packages/stauth/laravel-stauth)
[![composer.lock available](https://poser.pugx.org/stauth/laravel-stauth/composerlock)](https://packagist.org/packages/stauth/laravel-stauth)

Staging server athorization package, alternative for .htaccess, register at [stauth.io](https://www.stauth.io/)


## Installation


```bash
composer require stauth/laravel-stauth
```

### Local and staging

If you don't want Stauth service provider to be exeuted at production environment, create `StauthProtectionServiceProvider` 

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stauth\StauthServiceProvider;

class StauthProtectionServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'staging')) {
            $this->app->register(StauthServiceProvider::class);
        }
    }
}
```

And add it to `config/app.php` below `AppServiceProvider`:

```php

'providers' => [

        /**
         * Stauth app access protection
         */
        App\Providers\StauthProtectionServiceProvider::class,     
],
```

### Production

If you don't mind Stauth service provider being executed at production environment, or you want to protect your production env, add it directly at `providers` array in `config/app.php`.

```php

'providers' => [

        /**
         * Staging access control
         */
        Stauth\StauthServiceProvider::class,        
],
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
            ...
            \Stauth\Middleware\StauthProtection::class,
    ],
```

Generate access token at [stauth.io](https://www.stauth.io) and add it as a `STAUTH_ACCESS_TOKEN` param in `.env` file:

```bash
STAUTH_ACCESS_TOKEN=verylongchainoflettersmixedwithsomerandomnumbers123

```

By default protected environment is `staging`, in order to change this, add `STAUTH_PROTECTED_ENV` param in `.env` file: 

```bash
STAUTH_PROTECTED_ENV=local
```

## Other
If you want to know or do more, read below.

### Publish configuration

You can publish configuration and update required params in php file:

```bash

php artisan vendor:publish --provider="Stauth\StauthServiceProvider" --tag=config

```

### Cache

Please keep in mind that this package takes adventage of `csrf_token`, therefore it is important to exclude both routes `/stauth/protected` and `/stauth/authorize` from any response caching engines.
