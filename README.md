[![](https://user-images.githubusercontent.com/60096509/91668964-54ecd500-eb11-11ea-9c35-e8f0b20b277a.png)](https://sandwave.io)

# European VAT utility for PHP

// TODO: Use badges of this project.

## Usage

```php
use SandwaveIo\Vat\Vat;

Vat::validateVatNumber("YOURVATNUMBERHERE"); // true

Vat::countryInEurope('NL'); // true

Vat::europeanVatRate("YOURVATNUMBERHERE", "NL"); // 0.0
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
