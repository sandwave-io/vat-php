<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Tests;

use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\Countries\Iso2;
use SandwaveIo\Vat\VatRates\ResolvesVatRates;

/** @covers \SandwaveIo\Vat\Vat */
class VatServiceTest extends TestCase
{
    /** @dataProvider countryTestData */
    public function testCountryInEu(bool $validCountry, bool $inEu, bool $result): void
    {
        $service = new VatStub();
        $mock = $this->createMock(Iso2::class);
        $mock->method('isCountryValid')->willReturn($validCountry);
        $mock->method('isCountryInEu')->willReturn($inEu);
        $service->setCountries($mock);

        Assert::assertEquals($result, $service->countryInEurope('NL'));
    }

    /** @return Generator<array> */
    public function countryTestData(): Generator
    {
        yield [true, true, true];
        yield [true, false, false];
        yield [false, true, false];
        yield [false, false, false];
    }

    /** @dataProvider euVatRateTestData */
    public function testEuropeanVatRate(string $countryCode, bool $validCountry, bool $inEu, ?float $rate, float $result): void
    {
        $isoMock = $this->createMock(Iso2::class);
        $isoMock->method('isCountryValid')->willReturn($validCountry);
        $isoMock->method('isCountryInEu')->willReturn($inEu);

        $vatResolverMock = $this->createMock(ResolvesVatRates::class);
        $vatResolverMock->method('getDefaultVatRateForCountry')->willReturn($rate);

        $service = new VatStub();
        $service->setCountries($isoMock);
        $service->setVatRateResolver($vatResolverMock);

        Assert::assertEquals($result, $service->europeanVatRate($countryCode));
    }

    /** @return Generator<array> */
    public function euVatRateTestData(): Generator
    {
        yield ['NL', true, true, 21.0, 21.0];
        yield ['LU', true, true, 17.0, 17.0];
        yield ['OM', true, false, 3.0, 0.0];
        yield ['CA', true, false, null, 0.0];
        yield ['XX', false, false, null, 0.0];
    }
}
