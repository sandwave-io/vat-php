<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\VatNumbers;

interface ValidatesVatNumbers
{
    public function verifyVatNumber(string $vatNumber, string $countryCode): bool;
}
