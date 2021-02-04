<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Tests\VatNumbers;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\Exceptions\VatNumberVerifyFailedException;
use SandwaveIo\Vat\VatNumbers\ViesClient;
use SoapClient;
use SoapFault;

/** @covers \SandwaveIo\Vat\VatNumbers\ViesClient */
class VatClientTest extends TestCase
{
    public function testVerifyVatNumber(): void
    {
        $validated = unserialize(include 'numbers_snapshot.php');
        $mockedSoapClient = $this->getMockFromWsdl(ViesClient::WSDL);
        $mockedSoapClient->method('checkVat')->willReturn($validated);

        /** @var SoapClient $mockedSoapClient */
        $client = new ViesClient($mockedSoapClient);

        $result = $client->verifyVatNumber('NL138250460B01', 'NL');
        Assert::assertEquals(true, $result);
    }

    public function testGetRatesException(): void
    {
        $mockedSoapClient = $this->getMockFromWsdl(ViesClient::WSDL);
        $mockedSoapClient->method('checkVat')
            ->willThrowException(new SoapFault('test', 'testtest'));

        /** @var SoapClient $mockedSoapClient */
        $client = new ViesClient($mockedSoapClient);

        $this->expectException(VatNumberVerifyFailedException::class);
        $client->verifyVatNumber('NL138250460B01', 'NL');
    }
}
