# Eloquent GAQL

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mk-ryan1988/elequent-gaql.svg?style=flat-square)](https://packagist.org/packages/mk-ryan1988/elequent-gaql)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/mk-ryan1988/elequent-gaql/run-tests?label=tests)](https://github.com/mk-ryan1988/elequent-gaql/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/mk-ryan1988/elequent-gaql/Check%20&%20fix%20styling?label=code%20style)](https://github.com/mk-ryan1988/elequent-gaql/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/mk-ryan1988/elequent-gaql.svg?style=flat-square)](https://packagist.org/packages/mk-ryan1988/elequent-gaql)

Allows you to write Google Ad's Query strings the [Laravel Eloquent](https://laravel.com/docs/8.x/queries) way.

## Installation

You can install the package via composer:

```bash
composer require mk-ryan1988/elequent-gaql
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="MkRyan1988\GaqlBuilder\GaqlBuilderServiceProvider" --tag="elequent-gaql-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="MkRyan1988\GaqlBuilder\GaqlBuilderServiceProvider" --tag="elequent-gaql-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$elequent-gaql = new MkRyan1988\GaqlBuilder();
echo $elequent-gaql->echoPhrase('Hello, Spatie!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Mark Ryan](https://github.com/mk-ryan1988)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
