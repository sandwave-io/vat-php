<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Tests\VatRates;

use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\Exceptions\VatFetchFailedException;
use SandwaveIo\Vat\VatRates\TaxesEuropeDatabaseClient;
use SoapClient;
use SoapFault;

/** @covers \SandwaveIo\Vat\VatRates\TaxesEuropeDatabaseClient */
class TedbClientTest extends TestCase
{
    /** @dataProvider soapTestData */
    public function testGetRates(?object $rates, ?float $rate): void
    {
        /** @var MockObject&SoapClient $mockedSoapClient */
        $mockedSoapClient = $this->getMockFromWsdl(TaxesEuropeDatabaseClient::WSDL);
        $mockedSoapClient->method('__soapCall')->with('retrieveVatRates')->willReturn($rates);
        $client = new TaxesEuropeDatabaseClient($mockedSoapClient);

        $result = $client->getDefaultVatRateForCountry('NL');
        Assert::assertSame($rate, $result);
    }

    public function testGetRatesException(): void
    {
        /** @var MockObject&SoapClient $mockedSoapClient */
        $mockedSoapClient = $this->getMockFromWsdl(TaxesEuropeDatabaseClient::WSDL);
        $mockedSoapClient->method('__soapCall')
            ->with('retrieveVatRates')
            ->willThrowException(new SoapFault('test', 'testtest'));
        $client = new TaxesEuropeDatabaseClient($mockedSoapClient);

        $this->expectException(VatFetchFailedException::class);
        $client->getDefaultVatRateForCountry('NL');
    }

    /** @return Generator<array> */
    public function soapTestData(): Generator
    {
        yield [unserialize(include 'rates_snapshot.php'), 21.0];
        yield [null, null];
        yield [(object) [], null];
        yield [(object) ['vatRateResults' => []], null];
    }
}
