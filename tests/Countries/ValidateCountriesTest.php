<?php declare(strict_types = 1);

namespace SandwaveIo\Tests\Countries;

use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\Countries\Iso2;

/** @covers \SandwaveIo\Vat\Countries\Iso2 */
final class ValidateCountriesTest extends TestCase
{
    /**  @dataProvider countriesProvider */
    public function testValidateCountries(string $countryCode, bool $valid, bool $inEu): void
    {
        $countries = new Iso2();
        Assert::assertSame($valid, $countries->isCountryValid($countryCode), 'Unexpected value, country code valid/invalid.');
        Assert::assertSame($inEu, $countries->isCountryInEu($countryCode), 'Unexpected value, country in/not-in eu.');
    }

    /**
     * @return Generator<array<mixed>>
     */
    public static function countriesProvider(): Generator
    {
        yield ['NL', true, true];
        yield ['OM', true, false];
        yield ['XX', false, false];
    }
}
