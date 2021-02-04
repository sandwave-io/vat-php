<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Tests;

use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\Countries\Iso2;
use SandwaveIo\Vat\Vat;
use SandwaveIo\Vat\VatNumbers\VerifiesVatNumbers;

/** @covers \SandwaveIo\Vat\Vat */
class VatServiceTest extends TestCase
{
    /** @dataProvider countryTestData */
    public function testCountryInEu(bool $validCountry, bool $inEu, bool $result): void
    {
        $vatVerifyMock = $this->createMock(VerifiesVatNumbers::class);
        $service = new Vat($vatVerifyMock);
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

    /**
     * @dataProvider vatNumberTestData
     * @Depends ValidateCountriesTest::testValidateCountries
     */
    public function testValidateVatNumber(bool $valid, string $vatNumber, string $countryCode, bool $result): void
    {
        $vatVerifyMock = $this->createMock(VerifiesVatNumbers::class);
        $vatVerifyMock->method('verifyVatNumber')->willReturn($valid);
        $service = new Vat($vatVerifyMock);

        Assert::assertEquals($result, $service->validateVatNumber($vatNumber, $countryCode));
    }

    /** @return Generator<array> */
    public function vatNumberTestData(): Generator
    {
        yield [true, 'NL138250460B01', 'NL', true];
        yield [true, 'NL138250460B01', 'AZ', false];
        yield [false, 'invalidVatNumber', 'DE', false];
        yield [false, 'invalidVatNumber', 'IN', false];
    }
}
