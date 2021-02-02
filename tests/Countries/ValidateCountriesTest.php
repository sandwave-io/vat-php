<?php declare(strict_types = 1);

namespace SandwaveIo\Tests\Countries;

use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\Countries\Iso2;

/** @covers Iso2 */
class ValidateCountriesTest extends TestCase
{
    /** @dataProvider countriesProvider */
    public function testValidateCountries(string $countryCode, bool $valid, bool $inEu): void
    {
        $countries = new Iso2();
        Assert::assertEquals($valid, $countries->isCountryValid($countryCode), 'Unexpected value, country code valid/invalid.');
        Assert::assertEquals($inEu, $countries->isCountryInEu($countryCode), 'Unexpected value, country in/not-in eu.');
    }

    /**
     * @return Generator<array>
     */
    public function countriesProvider(): Generator
    {
        yield ['NL', true, true];
        yield ['OM', true, false];
        yield ['XX', false, false];
    }
}
