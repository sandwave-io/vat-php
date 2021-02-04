<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Tests;

use SandwaveIo\Vat\Countries\Iso2;
use SandwaveIo\Vat\Vat;
use SandwaveIo\Vat\VatRates\ResolvesVatRates;

class VatStub extends Vat
{
    public function setCountries(Iso2 $countries): void
    {
        $this->countries = $countries;
    }

    public function setVatRateResolver(ResolvesVatRates $vatRateResolver): void
    {
        $this->vatRateResolver = $vatRateResolver;
    }
}
