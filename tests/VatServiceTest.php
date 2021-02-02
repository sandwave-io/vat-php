<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\Vat;

/** @covers Vat */
class VatServiceTest extends TestCase
{
    public function testCountryInEu(): void
    {
        $service = new Vat();
        Assert::assertEquals(true, $service->countryInEurope('NL'), 'Netherlands should be in the EU.');
        Assert::assertEquals(false, $service->countryInEurope('OM'), 'Oman should not be in the EU.');
        Assert::assertEquals(false, $service->countryInEurope('XX'), 'XX is not a valid country.');
    }
}
