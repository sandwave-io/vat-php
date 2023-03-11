<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Tests;

use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\Countries\ResolvesCountries;
use SandwaveIo\Vat\Vat;
use SandwaveIo\Vat\VatNumbers\ValidatesVatNumbers;
use SandwaveIo\Vat\VatRates\ResolvesVatRates;

/** @covers \SandwaveIo\Vat\Vat */
final class VatServiceTest extends TestCase
{
    /** @dataProvider countryTestData */
    public function testCountryInEu(bool $validCountry, bool $inEu, bool $result): void
    {
        $mock = $this->createMock(ResolvesCountries::class);
        $mock->method('isCountryValid')->willReturn($validCountry);
        $mock->method('isCountryInEu')->willReturn($inEu);
        $service = new Vat($mock);

        Assert::assertSame($result, $service->countryInEurope('NL'));
    }

    /** @return Generator<array<mixed>> */
    public static function countryTestData(): Generator
    {
        yield [true, true, true];
        yield [true, false, false];
        yield [false, true, false];
        yield [false, false, false];
    }

    /** @dataProvider vatNumberTestData */
    public function testValidateVatNumber(
        bool $valid,
        bool $validCountry,
        bool $inEu,
        string $vatNumber,
        string $countryCode,
        bool $result
    ): void {
        $countryResolverMock = $this->createMock(ResolvesCountries::class);
        $countryResolverMock->method('isCountryValid')->willReturn($validCountry);
        $countryResolverMock->method('isCountryInEu')->willReturn($inEu);

        $vatVerifyMock = $this->createMock(ValidatesVatNumbers::class);
        $vatVerifyMock->method('verifyVatNumber')->willReturn($valid);
        $service = new Vat($countryResolverMock, null, $vatVerifyMock);

        Assert::assertSame($result, $service->validateEuropeanVatNumber($vatNumber, $countryCode));
    }

    /** @return Generator<array<mixed>> */
    public static function vatNumberTestData(): Generator
    {
        yield [true, true, true, '138250460B01', 'NL', true];
        yield [true, true, true, 'NL138250460B01', 'NL', true];
        yield [true, true, false, 'NL138250460B01', 'AZ', false];
        yield [false, true, true, 'invalidVatNumber', 'DE', false];
        yield [false, true, false, 'invalidVatNumber', 'IN', false];
    }

    /** @dataProvider euVatRateTestData */
    public function testEuropeanVatRate(string $countryCode, bool $validCountry, bool $inEu, ?float $rate, float $result): void
    {
        $countryResolverMock = $this->createMock(ResolvesCountries::class);
        $countryResolverMock->method('isCountryValid')->willReturn($validCountry);
        $countryResolverMock->method('isCountryInEu')->willReturn($inEu);

        $vatResolverMock = $this->createMock(ResolvesVatRates::class);
        $vatResolverMock->method('getDefaultVatRateForCountry')->willReturn($rate);

        $service = new Vat($countryResolverMock, $vatResolverMock);

        Assert::assertSame($result, $service->europeanVatRate($countryCode));
    }

    /** @return Generator<array<mixed>> */
    public static function euVatRateTestData(): Generator
    {
        yield ['NL', true, true, 21.0, 21.0];
        yield ['LU', true, true, 17.0, 17.0];
        yield ['OM', true, false, 3.0, 0.0];
        yield ['CA', true, false, null, 0.0];
        yield ['XX', false, false, null, 0.0];
    }
}
