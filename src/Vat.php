<?php declare(strict_types = 1);

namespace SandwaveIo\Vat;

use SandwaveIo\Vat\Countries\Iso2;

final class Vat
{
    private Iso2 $countries;
    private ViesClient $viesClient;

    public function __construct(?ViesClient $viesClient = null)
    {
        $this->countries = new Iso2();
        $this->viesClient = $viesClient ?? new ViesClient();
    }

    public function validateVatNumber(string $vatNumber, string $countryCode): bool
    {
        // The VIES service is for Europe. Other VAT numbers are not checked and assumed invalid.
        if (! $this->countryInEurope($countryCode)) {
            return false;
        }

        return $this->viesClient->checkVat($vatNumber, $countryCode);
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
