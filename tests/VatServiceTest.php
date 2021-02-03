<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Tests;

use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\Countries\Iso2;
use SandwaveIo\Vat\Vat;
use SandwaveIo\Vat\ViesClient;

/** @covers \SandwaveIo\Vat\Vat */
class VatServiceTest extends TestCase
{
    /** @dataProvider countryTestData */
    public function testCountryInEu(bool $validCountry, bool $inEu, bool $result): void
    {
        $service = new Vat();
        $mock = $this->createMock(Iso2::class);
        $mock->method('isCountryValid')->willReturn($validCountry);
        $mock->method('isCountryInEu')->willReturn($inEu);
        $service->setCountries($mock);

        Assert::assertEquals($result, $service->countryInEurope('NL'));
    }

    /** @dataProvider vatNumberTestData */
    public function testValidateVatNumber(bool $valid, string $vatNumber, string $countryCode, bool $result): void
    {
        $mock = $this->createMock(ViesClient::class);
        $mock->method('checkVat')->willReturn($valid);
        $service = new Vat($mock);

        Assert::assertEquals($result, $service->validateVatNumber($vatNumber, $countryCode));
    }

    /** @return Generator<array> */
    public function countryTestData(): Generator
    {
        yield [true, true, true];
        yield [true, false, false];
        yield [false, true, false];
        yield [false, false, false];
    }

    /** @return Generator<array> */
    public function vatNumberTestData(): Generator
    {
        // TODO: use semi real codes.
        yield [true, '123241', 'NL', true];
        yield [true, '123241', 'AZ', false];
        yield [false, 'invalidVatNumber', 'DE', false];
        yield [false, 'invalidVatNumber', 'IN', false];
    }
}
