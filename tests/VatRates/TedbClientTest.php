<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Tests\VatRates;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\VatRates\TaxesEuropeDatabaseClient;
use SoapClient;

/** @covers \SandwaveIo\Vat\VatRates\TaxesEuropeDatabaseClient */
class TedbClientTest extends TestCase
{
    private object $rates;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rates = unserialize(include 'rates_snapshot.php');
    }

    public function testGetRates(): void
    {
        $mockedSoapClient = $this->getMockFromWsdl(TaxesEuropeDatabaseClient::WSDL);
        $mockedSoapClient->method('__soapCall')->with('retrieveVatRates')->willReturn($this->rates);

        /** @var SoapClient $mockedSoapClient */
        $client = new TaxesEuropeDatabaseClient($mockedSoapClient);

        $result = $client->getDefaultVatRateForCountry('NL');
        Assert::assertEquals((float) 21, $result);
    }
}
