[![](https://user-images.githubusercontent.com/60096509/91668964-54ecd500-eb11-11ea-9c35-e8f0b20b277a.png)](https://sandwave.io)

# European VAT utility for PHP

[![codecov](https://codecov.io/gh/sandwave-io/vat-php/branch/main/graph/badge.svg?token=Z9OOFA247I)](https://codecov.io/gh/sandwave-io/vat-php)
[![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/sandwave-io/vat-php/ci.yml?branch=main)](https://packagist.org/packages/sandwave-io/vat)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/sandwave-io/vat)](https://packagist.org/packages/sandwave-io/vat)
[![Packagist PHP Version Support](https://img.shields.io/packagist/v/sandwave-io/vat)](https://packagist.org/packages/sandwave-io/vat)
[![Packagist Downloads](https://img.shields.io/packagist/dt/sandwave-io/vat)](https://packagist.org/packages/sandwave-io/vat)

## Usage

```shell
composer require sandwave-io/vat
```

```php
$vatService = new \SandwaveIo\Vat\Vat;

$vatService->validateEuropeanVatNumber("YOURVATNUMBERHERE", "COUNTRYCODE"); // true

$vatService->countryInEurope('NL'); // true

$vatService->europeanVatRate("COUNTRYCODE"); // 0.0
```

## External documentation

* [VIES API](https://ec.europa.eu/taxation_customs/vies/technicalInformation.html)
* [TEDB- "Taxes in Europe" database](https://ec.europa.eu/taxation_customs/economic-analysis-taxation/taxes-europe-database-tedb_en)

## How to contribute

Feel free to create a PR if you have any ideas for improvements. Or create an issue.

* When adding code, make sure to add tests for it (phpunit).
* Make sure the code adheres to our coding standards (use php-cs-fixer to check/fix).
* Also make sure PHPStan does not find any bugs.

```bash
vendor/bin/php-cs-fixer fix

vendor/bin/phpstan analyze

vendor/bin/phpunit --coverage-text
```

These tools will also run in GitHub actions on PR's and pushes on master.

### About the testsuite

There is also an integration test, in order to skip this (heavy) test, run:
```bash
vendor/bin/phpunit --exclude=large
```

We generate coverage in PHPUnit. On the CI we use XDebug to do that. If you have XDebug installed, you can run:
```bash
vendor/bin/phpunit --coverage-text

# or generate an interactive report.
vendor/bin/phpunit --coverage-html=coverage_report
```

Alternatively, you can use _PHPDBG_ as coverage driver:
```bash
phpdbg -qrr vendor/bin/phpunit --coverage-text
```
