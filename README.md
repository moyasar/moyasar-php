# moyasar-php

[![PHP version](https://badge.fury.io/ph/moyasar%2Fmoyasar.svg)](https://badge.fury.io/ph/moyasar%2Fmoyasar)

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

After installing the library using composer and including `autoload.php`, the API key need
to be set in order to use the services.

```php
include_once 'vendor/autoload.php';

\Moyasar\Moyasar::setApiKey('api-key');
```

Setup is complete, create an instance of the service you need and start using it.

#### Payment

Note: Moyasar does not allow creating payments using the API (with some exceptions), instead you can use
the [payment form](https://moyasar.com/docs/payments/create-payment/mpf/). That is why, wrapper libraries does not support it.

---

To fetch a payment, just simply do the following:

```php
$paymentService = new \Moyasar\Providers\PaymentService();

$payment = $paymentService->fetch('ae5e8c6a-1622-45a5-b7ca-9ead69be722e');
```

An instance of `Payment` will be returned, that has the data in addition to being able
to perform operations like `update`, `refund`, `capture`, `void` on that payment instance,
which we will get back to later.

---

To list payments associated with your account, simply do the following:

```php
$paymentService = new \Moyasar\Providers\PaymentService();

$paginationResult = $paymentService->all();

$payments = $paginationResult->result;
```

The `all` method will return an instance of `PaginationResult` this contains meta data
about our result, like `currentPage`, `totalPages` etc...

To get the payments from this object, we just read the `result` property of that object.

---

The `all` method accepts an instance of `Search` or an array, this allows us to filter
results and move along pages. It is quite simple to use:

```php
$search = \Moyasar\Search::query()->status('paid')->page(2);

$paginationResult = $paymentService->all($search);
```

The following methods are supported:

- `id($id)`
- `status($status)`
- `source($source)`
- `page($page)`
- `createdAfter($date)`
- `createdBefore($date)`

---

Once we fetch the desired payment, we can either `update` the description, `refund` it,
`capture` it, or `void` it.

```php
$payment->update('new description here');

// OR

$payment->refund(1000); // 10.00 SAR

// OR

$payment->capture(1000);

// OR

$payment->void();
```

#### Invoice

For invoices, fetching and listing them is the same as payments, instead we use `InvoiceService`.

Although, we can use the API to create a new invoice, by doing the following:

```php
$invoiceService = new \Moyasar\Providers\InvoiceService();

$invoiceService->create([
    'amount' => 1000000, // 10000.00 SAR
    'currency' => 'SAR',
    'description' => 'iPhone XII Purchase',
    'callback_url' => 'http://www.example.com/invoice-status-changed', // Optional
    'expired_at' => '2020-01-20' // Optional
]);
```

---

With an instance of `Invoice`, we can either `update`, or `cancel` a given instance.

```php
$invoice->update([
    'amount' => 900000, // 9000.00 SAR
    'currency' => 'SAR',
    'description' => 'iPhone XII Purchase (Updated)',
    'callback_url' => 'http://www.example.com/invoice-status-changed', // Optional
    'expired_at' => '2020-01-25' // Optional
]);

// OR

$invoice->cancel();
```

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

---

Or if you want a quick way to use these services, you can use the `Payment` and `Invoice` facades:

- `Moyasar\Facades\Payment`
- `Moyasar\Facades\Invoice`

For example:

```php
$payment = \Moyasar\Facades\Payment::fetch('id');
```

## Contributing

Bug reports and pull requests are welcome on GitHub at https://github.com/[USERNAME]/moyasar-php. This project is intended to be a safe, welcoming space for collaboration, and contributors are expected to adhere to the [Contributor Covenant](contributor-covenant.org) code of conduct.

## License

The package is available as open source under the terms of the [MIT License](http://opensource.org/licenses/MIT).
