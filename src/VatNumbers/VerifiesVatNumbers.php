<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\VatNumbers;

interface VerifiesVatNumbers
{
    public function verifyVatNumber(string $vatNumber, string $countryCode): bool;
}
