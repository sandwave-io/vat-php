<?php
namespace SandwaveIo\Vat\Tests\VatRates;

use DateTime;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\VatRates\TaxesEuropeDatabaseClient;

class DebugTest extends TestCase
{
    public function test(): void
    {
        $client = new TaxesEuropeDatabaseClient();

        var_dump($client->getRates('NL', new DateTime()));
    }
}