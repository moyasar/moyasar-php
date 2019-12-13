# moyasar-php

[![PHP version](https://badge.fury.io/ph/moyasar%2Fmoyasar-php.svg)](https://badge.fury.io/ph/moyasar%2Fmoyasar-php)

Moyasar PHP wrapper library

## Documentation

See the [PHP API docs](https://moyasar.com/docs/api/?php)

## Requirements

- PHP 5.6.0
- guzzlehttp/guzzle: ^6.3.0
- laravel/framework (Optional)

#### Notes

- Please note that starting from version `1.0.0` the library was rewritten with breaking changes, so please do not update
unless you need the new version. If you are new, it is recommended to use the new version.
- To use the PHP stream handler, allow_url_fopen must be enabled in your system's php.ini.
- To use the cURL handler, you must have a recent version of cURL >= 7.19.4 compiled with OpenSSL and zlib.
- Please note that in version `0.5.0` the library name has been changed from `moyasar-php` to `moyasar` 

## Installation

You can install it via [composer](https://getcomposer.org/)

    $ composer require moyasar/moyasar

## Usage

#### In a Standard Project

TODO

#### Laravel

First thing we need to add `moyasar/moyasar` to our Laravel project, to do it we need:

    $ composer require moyasar/moyasar

After that, moyasar services need to be configured, so let us publish the configuration file:

    $ php artisan vendor:publish --provider="Moyasar\Providers\LaravelServiceProvider"

Now edit `config/moyasar.php` and add your API key, by default the API key is read from
an environment variable called `MOYASAR_API_KEY`, thus `.env` can be used to add the key.

```env
MOYASAR_API_KEY=<Your_API_Key>
```

If everything goes to plan, you should be able to get `PaymentService` and `InvoiceService`
from laravel service container by simply called `app` helper function

```php
app(PaymentService::class)
```

```php
app(InvoiceService::class)
```

Or inside your controller, you can simply type-hint one of the services in the constructor:

```php
public function __construct(PaymentService $paymentService)
{
    $this->paymentService = $paymentService;
}
```

## Contributing

Bug reports and pull requests are welcome on GitHub at https://github.com/[USERNAME]/moyasar-php. This project is intended to be a safe, welcoming space for collaboration, and contributors are expected to adhere to the [Contributor Covenant](contributor-covenant.org) code of conduct.

## License

The package is available as open source under the terms of the [MIT License](http://opensource.org/licenses/MIT).
