<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Tests;

use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\Countries\Iso2;
use SandwaveIo\Vat\Vat;

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

    /** @return Generator<array> */
    public function countryTestData(): Generator
    {
        yield [true, true, true];
        yield [true, false, false];
        yield [false, true, false];
        yield [false, false, false];
    }
}
