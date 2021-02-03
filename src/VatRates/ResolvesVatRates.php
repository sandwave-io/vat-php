<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\VatRates;

use DateTime;

interface ResolvesVatRates
{
    public function getDefaultVatRateForCountry(string $countryCode, ?DateTime $dateTime = null): ?float;
}
