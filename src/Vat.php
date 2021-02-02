<?php declare(strict_types = 1);

namespace SandwaveIo\Vat;

use SandwaveIo\Vat\Countries\Iso2;

final class Vat
{
    private Iso2 $countries;

    public function __construct()
    {
        $this->countries = new Iso2();
    }

    public function validateVatNumber(string $vatNumber): bool
    {
        // TODO: Implement
        return true;
    }

    public function countryInEurope(string $countryCode): bool
    {
        return $this->countries->isCountryValid($countryCode) && $this->countries->isCountryInEu($countryCode);
    }

    public function europeanVatRate(string $vatNumber, string $countryCode): float
    {
        // TODO: Implement
        return 0.0;
    }

    public function setCountries(Iso2 $countries): void
    {
        $this->countries = $countries;
    }
}
