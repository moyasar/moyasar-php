# Changelog
All notable changes to this library will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
* Nothing New

## [1.0.2] - 2020-12-28

Upgraded GuzzleHttp version in composer.json

## [1.0.0] - 2020-04-06

This version of the library is a complete reimplementation from ground up.
Please checkout the following sections to see what changed

### Summary

There is many changes to the library API, so let's get going.
First we need to set the secret API key:

Before

```php
\Moyasar\Client::setApiKey("API-KEY");
```

After

```php
\Moyasar\Moyasar::setApiKey('API-KEY');
``` 

#### Payment and Invoice Fetch

In case of fetching a payment, we use `PaymentService` class.

Before:

```php
$data = \Moyasar\Payment::fetch("760878ec-d1d3-5f72-9056-191683f55872");
$data = \Moyasar\Invoice::fetch("760878ec-d1d3-5f72-9056-191683f55872");
```

After:

```php
$paymentService = new \Moyasar\Providers\PaymentService();
$invoiceService = new \Moyasar\Providers\InvoiceService();

$payment = $paymentService->fetch('760878ec-d1d3-5f72-9056-191683f55872');
$invoice = $invoiceService->fetch('760878ec-d1d3-5f72-9056-191683f55872');
```

The returned result is of type `\Moyasar\Payment` and `\Moyasar\Invoice`
respectively and has the ability to perform operations on that
payment instance like `update`, `refund`, `capture`, and `void`, and operations
on the other invoice instance like `update` and `cancel`.

#### Payment and Invoice Listing

Before:

```php
$payments = \Moyasar\Payment::all();
$invoices = \Moyasar\Invoice::all();
```

Listing payments or invoices using `list` method in both `PaymentService` and `InvoiceService` class
returns a `PaginationResult` instance:

```php
$paymentService = new \Moyasar\Providers\PaymentService();
$invoiceService = new \Moyasar\Providers\InvoiceService();

$search = \Moyasar\Search::query();
$search = $search->createdAfter('date');
$search = $search->createdBefore('date');
$search = $search->id('id');
$search = $search->page('page-number-to-list');
$search = $search->source('payment-source-type');
$search = $search->status('status');

$paymentListing = $paymentService->all($search);
$payments = $paymentListing->result;

$invoiceListing = $invoiceService->all();
$invoices = $invoiceListing->result;

$invoiceListing->currentPage; // Current Page
$invoiceListing->nextPage; // Next Page or null
$invoiceListing->previousPage; // Previous Page or null
$invoiceListing->totalCount; // Total Invoices
$invoiceListing->totalPages; // Total Pages
```

---

### Removed
* `Client` class
* `HttpRequestNotFound` class
* `Invoice` class (Used to perform all invoice operations)
* `Payment` class (Used to perform all payment operations)

### Added
* `Moyasar` class that stores API keys and version information
* `HttpClient` interface
* `HttpClient` class, implements all HTTP transactions
* `Resource` class
* `OnlineResource` class that represent resources that can perform some operations
* `Invoice` class, extends `OnlineResource`, used to perform operations related to a single invoice
* `Payment` class, extends `OnlineResource`
* `InvoiceService` service class, used to create, fetch, and list invoices
* `PaymentService` service class, used to fetch, and list payments
* `Search` class, used to provide search parameters to list methods in `InvoiceService` and `PaymentService`
* `Source` class to represent a payment source for `Payment`
* `CreditCard` class that represents `creditcard` payment method in Moyasar's API
* `Sadad` class that represents `sadad` payment method in Moyasar's API
* `PaginationResult` class, returned by list methods on `InvoiceService` and `PaymentService`
* `BaseException` as a base for all library exceptions
* `ApiException` class that represent error returned by Moyasar's API
* `ValidationException` thrown when data validation fails before sending a request to the backend
* `Invoice` facade for Laravel
* `Payment` facade for Laravel
* `LaravelServiceProvider` class, used to automatically register Moyasar's services in Laravel's service container
* `GuzzleClientFactory` factory class
* Unit Testing
* Laravel Configuration File `config/config.php`

### Changes
* The library now requires PHP version `5.6.0` or higher instead of `5.5.0` 


## [0.5.0] - 2019-03-27
### Removed
* Disabled the ability to create payments from the library. We recommend you to use [Moyasar Payment Form]

### Changes
* In this version, we change our library name to be only the word `moyasar`.


## [v0.4.3] - 2019-01-28
### Added
* Add ability in PHP wrappers to do:
    * Update a payment.
    * Update an invoice.
    * Cancel an invoice.


## [v0.4.0] - 2016-11-02
### Changed
* This version has a breaking change. We rename our main classes to be single not plural. So `Payments` class now `Payment` and `Invoices` changed to `Invoice`


## [v0.3.5] - 2016-09-05
### Changed
* Fixed List Method


## [v0.3.0] - 2016-07-19
* First Release


[Unreleased]: https://github.com/moyasar/moyasar-php/compare/1.0.0...HEAD
[1.0.0]: https://github.com/moyasar/moyasar-php/releases/tag/1.0.0
[0.5.0]: https://github.com/moyasar/moyasar-php/releases/tag/0.5.0
[v0.4.3]: https://github.com/moyasar/moyasar-php/releases/tag/v0.4.3
[v0.4.0]: https://github.com/moyasar/moyasar-php/releases/tag/v0.4.0
[v0.3.5]: https://github.com/moyasar/moyasar-php/releases/tag/v0.3.5
[v0.3.0]: https://github.com/moyasar/moyasar-php/releases/tag/v0.3.0

[Moyasar Payment Form]: https://moyasar.com/docs/payments/create-payment/mpf/