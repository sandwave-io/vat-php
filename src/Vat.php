<?php declare(strict_types = 1);

namespace SandwaveIo\Vat;

use SandwaveIo\Vat\Countries\Iso2;
use SandwaveIo\Vat\VatNumbers\VerifiesVatNumbers;
use SandwaveIo\Vat\VatNumbers\ViesClient;

final class Vat
{
    private Iso2 $countries;
    private VerifiesVatNumbers $vatNumberVerifier;

    public function __construct(?VerifiesVatNumbers $vatNumberVerifier = null)
    {
        $this->countries = new Iso2();
        $this->vatNumberVerifier = $vatNumberVerifier ?? new ViesClient();
    }

    public function validateVatNumber(string $vatNumber, string $countryCode): bool
    {
        // The VIES service is EU only. Non-EU VAT numbers are not checked and assumed invalid.
        if (! $this->countryInEurope($countryCode)) {
            return false;
        }

        return $this->vatNumberVerifier->verifyVatNumber($vatNumber, $countryCode);
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
