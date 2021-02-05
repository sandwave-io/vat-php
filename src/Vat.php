<?php declare(strict_types = 1);

namespace SandwaveIo\Vat;

use DateTimeImmutable;
use SandwaveIo\Vat\Countries\Iso2;
use SandwaveIo\Vat\Countries\ResolvesCountries;
use SandwaveIo\Vat\VatRates\ResolvesVatRates;
use SandwaveIo\Vat\VatRates\TaxesEuropeDatabaseClient;

final class Vat
{
    private ResolvesCountries $countryResolver;
    private ResolvesVatRates $vatRateResolver;

    public function __construct(?ResolvesCountries $countryResolver = null, ?ResolvesVatRates $vatRateResolver = null)
    {
        $this->countryResolver = $countryResolver ?? new Iso2();
        $this->vatRateResolver = $vatRateResolver ?? new TaxesEuropeDatabaseClient();
    }

    public function validateVatNumber(string $vatNumber): bool
    {
        // TODO: Implement
        return true;
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
