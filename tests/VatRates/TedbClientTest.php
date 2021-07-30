<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Tests\VatRates;

use Generator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use SandwaveIo\Vat\Exceptions\VatFetchFailedException;
use SandwaveIo\Vat\VatRates\TaxesEuropeDatabaseClient;
use SoapClient;
use SoapFault;

/** @covers \SandwaveIo\Vat\VatRates\TaxesEuropeDatabaseClient */
final class TedbClientTest extends TestCase
{
    /** @dataProvider soapTestData */
    public function testGetRates(string $countryCode, ?object $rates, ?float $rate): void
    {
        /** @var MockObject&SoapClient $mockedSoapClient */
        $mockedSoapClient = $this->getMockFromWsdl(TaxesEuropeDatabaseClient::WSDL);
        $mockedSoapClient->method('__soapCall')->with('retrieveVatRates')->willReturn($rates);
        $client = new TaxesEuropeDatabaseClient($mockedSoapClient);

        $result = $client->getDefaultVatRateForCountry($countryCode);
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
        yield ['NL', unserialize(include 'nl_rates_snapshot.php'), 21.0];
        yield ['GR', unserialize(include 'gr_rates_snapshot.php'), 24.0];
        yield ['NL', null, null];
        yield ['NL', (object) [], null];
        yield ['NL', (object) ['vatRateResults' => []], null];
    }

    public function testWithColdCache(): void
    {
        /** @var MockObject&SoapClient $mockedSoapClient */
        $mockedSoapClient = $this->getMockFromWsdl(TaxesEuropeDatabaseClient::WSDL);
        $mockedSoapClient
            ->expects(TestCase::once())
            ->method('__soapCall')
            ->with('retrieveVatRates')
            ->willReturn(unserialize(include 'nl_rates_snapshot.php'));

        $cache = $this->createMock(CacheInterface::class);
        $cache->expects(TestCase::once())->method('has')->willReturn(false);
        $cache->expects(TestCase::never())->method('get');
        $cache->expects(TestCase::once())->method('set');

        $client = new TaxesEuropeDatabaseClient($mockedSoapClient);
        $client->setCache($cache);
        $client->getDefaultVatRateForCountry('NL');
    }
    public function testWithWarmCache(): void
    {
        /** @var MockObject&SoapClient $mockedSoapClient */
        $mockedSoapClient = $this->getMockFromWsdl(TaxesEuropeDatabaseClient::WSDL);
        $mockedSoapClient->expects(TestCase::never())->method('__soapCall');

        $cache = $this->createMock(CacheInterface::class);
        $cache->expects(TestCase::once())->method('has')->willReturn(true);
        $cache->expects(TestCase::once())->method('get')->willReturn(unserialize(include 'nl_rates_snapshot.php'));
        $cache->expects(TestCase::never())->method('set');

        $client = new TaxesEuropeDatabaseClient($mockedSoapClient);
        $client->setCache($cache);
        $client->getDefaultVatRateForCountry('NL');
    }
}
