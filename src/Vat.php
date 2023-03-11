<?php declare(strict_types = 1);

namespace SandwaveIo\Vat;

use DateTimeImmutable;
use SandwaveIo\Vat\Countries\Iso2;
use SandwaveIo\Vat\Countries\ResolvesCountries;
use SandwaveIo\Vat\VatNumbers\ValidatesVatNumbers;
use SandwaveIo\Vat\VatNumbers\ViesClient;
use SandwaveIo\Vat\VatRates\ResolvesVatRates;
use SandwaveIo\Vat\VatRates\TaxesEuropeDatabaseClient;

final class Vat
{
    private ResolvesCountries $countryResolver;

    private ResolvesVatRates $vatRateResolver;

    private ValidatesVatNumbers $vatNumberVerifier;

    public function __construct(
        ?ResolvesCountries $countryResolver = null,
        ?ResolvesVatRates $vatRateResolver = null,
        ?ValidatesVatNumbers $vatNumberVerifier = null
    ) {
        $this->countryResolver = $countryResolver ?? new Iso2();
        $this->vatRateResolver = $vatRateResolver ?? new TaxesEuropeDatabaseClient();
        $this->vatNumberVerifier = $vatNumberVerifier ?? new ViesClient();
    }

    public function validateEuropeanVatNumber(string $vatNumber, string $countryCode): bool
    {
        // The VIES service is EU only. Non-EU VAT numbers are not checked and assumed invalid.
        if (! $this->countryInEurope($countryCode)) {
            return false;
        }

        // Most people include the Country Code as the first letters of the VAT number. VIES doesn't support this.
        if (str_starts_with($vatNumber, $countryCode)) {
            $vatNumber = substr($vatNumber, strlen($countryCode));
        }

        return $this->vatNumberVerifier->verifyVatNumber($vatNumber, $countryCode);
    }

    public function countryInEurope(string $countryCode): bool
    {
        return $this->countryResolver->isCountryValid($countryCode) && $this->countryResolver->isCountryInEu($countryCode);
    }

    public function europeanVatRate(string $countryCode, ?DateTimeImmutable $date = null, float $fallbackRate = 0.0): float
    {
        if (! $this->countryInEurope($countryCode)) {
            return $fallbackRate;
        }
        return $this->vatRateResolver->getDefaultVatRateForCountry($countryCode, $date) ?? $fallbackRate;
    }
}
