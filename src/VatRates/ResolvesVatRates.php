<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\VatRates;

use DateTimeImmutable;

interface ResolvesVatRates
{
    public function getDefaultVatRateForCountry(string $countryCode, ?DateTimeImmutable $dateTime = null): ?float;
}
