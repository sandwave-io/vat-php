<?php declare(strict_types = 1);

namespace SandwaveIo\Vat;

final class Vat
{
    public function validateVatNumber(string $vatNumber): bool
    {
        // TODO: Implement
        return true;
    }

    public function countryInEurope(string $countryCode): bool
    {
        // TODO: Implement
        return true;
    }

    public function europeanVatRate(string $vatNumber, string $countryCode): float
    {
        // TODO: Implement
        return 0.0;
    }
}
