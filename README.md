# A simple package to help integrate Cloudflare Turnstile.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ryangjchandler/laravel-cloudflare-turnstile.svg?style=flat-square)](https://packagist.org/packages/ryangjchandler/laravel-cloudflare-turnstile)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/ryangjchandler/laravel-cloudflare-turnstile/run-tests?label=tests)](https://github.com/ryangjchandler/laravel-cloudflare-turnstile/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/ryangjchandler/laravel-cloudflare-turnstile/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/ryangjchandler/laravel-cloudflare-turnstile/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ryangjchandler/laravel-cloudflare-turnstile.svg?style=flat-square)](https://packagist.org/packages/ryangjchandler/laravel-cloudflare-turnstile)

This packages provides helper for setting up and validating Cloudflare Turnstile CAPTCHA responses.

## Installation

You can install the package via Composer:

```bash
composer require ryangjchandler/laravel-cloudflare-turnstile
```

You should then add the following configuration values to your `config/services.php` file:

```php
return [

    // ...,

    'turnstile' => [
        'key' => env('TURNSTILE_SITE_KEY'),
        'secret' => env('TURNSTILE_SECRET_KEY'),
    ],

];
```

Visit Cloudflare to create your site key and secret key and add them to your `.env` file.

```
TURNSTILE_SITE_KEY="1x00000000000000000000AA"
TURNSTILE_SECRET_KEY="2x0000000000000000000000000000000AA"
```

## Usage

In your layout file, include the Turnstile scripts using the `@turnstileScripts` Blade directive. This should be added to the `<head>` of your document.

```blade
<html>
    <head>
        @turnstileScripts()
    </head>
    <body>
        {{ $slot }}
    </body>
</html>
```

Once that's done, you can use the `@turnstile` directive in `<form>` to output the appropriate markup with your site key configured.

```blade
<form action="/" method="POST">
    @turnstile()

    <button>
        Submit
    </button>
</form>
```

On the server, use the provided validation rule to validate the CAPTCHA response.

```php
use Illuminate\Validation\Rule;

public function submit(Request $request)
{
    $request->validate([
        'cf-turnstile-response' => ['required', Rule::turnstile()],
    ]);
}
```

If you prefer to not use a macro, you can resolve an instance of the rule from the container via dependency injection or the `app()` helper.

```php
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

public function submit(Request $request, Turnstile $turnstile)
{
    $request->validate([
        'cf-turnstile-response' => ['required', $turnstile],
    ]);
}
```

```php
use RyanChandler\LaravelCloudflareTurnstile\Rules\Turnstile;

public function submit(Request $request)
{
    $request->validate([
        'cf-turnstile-response' => ['required', app(Turnstile::class)],
    ]);
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Ryan Chandler](https://github.com/ryangjchandler)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
