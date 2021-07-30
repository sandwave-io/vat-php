<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\VatRates;

use DateTimeImmutable;
use Psr\SimpleCache\CacheInterface;

interface ResolvesVatRates
{
    public function setCache(?CacheInterface $cache): void;

    public function getDefaultVatRateForCountry(string $countryCode, ?DateTimeImmutable $dateTime = null): ?float;
}
