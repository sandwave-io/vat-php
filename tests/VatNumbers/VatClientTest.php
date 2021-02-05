<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Tests\VatNumbers;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SandwaveIo\Vat\Exceptions\VatNumberValidateFailedException;
use SandwaveIo\Vat\VatNumbers\ViesClient;
use SoapClient;
use SoapFault;

/** @covers \SandwaveIo\Vat\VatNumbers\ViesClient */
final class VatClientTest extends TestCase
{
    public function testVerifyVatNumber(): void
    {
        $validated = unserialize(include 'numbers_snapshot.php');
        /** @var MockObject&SoapClient $mockedSoapClient */
        $mockedSoapClient = $this->getMockFromWsdl(ViesClient::WSDL);
        $mockedSoapClient->method('checkVat')->willReturn($validated);

        $client = new ViesClient($mockedSoapClient);

        Assert::assertTrue($client->verifyVatNumber('NL138250460B01', 'NL'));
    }

    public function testGetRatesException(): void
    {
        /** @var MockObject&SoapClient $mockedSoapClient */
        $mockedSoapClient = $this->getMockFromWsdl(ViesClient::WSDL);
        $mockedSoapClient->method('checkVat')
            ->willThrowException(new SoapFault('test', 'testtest'));

        $client = new ViesClient($mockedSoapClient);

        $this->expectException(VatNumberValidateFailedException::class);
        $client->verifyVatNumber('NL138250460B01', 'NL');
    }
}
