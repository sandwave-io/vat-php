<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\VatNumbers;

use SandwaveIo\Vat\Exceptions\VatNumberValidateFailedException;
use SoapClient;
use SoapFault;

final class ViesClient implements ValidatesVatNumbers
{
    const WSDL = 'https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

    private ?SoapClient $client;

    public function __construct(?SoapClient $client = null)
    {
        $this->client = $client;
    }

    public function verifyVatNumber(string $vatNumber, string $countryCode): bool
    {
        $params = [
            'countryCode' => $countryCode,
            'vatNumber' => $vatNumber,
        ];

        try {
            $response = $this->getClient()->checkVat($params);

            return $response->valid;
        } catch (SoapFault $fault) {
            throw new VatNumberValidateFailedException(
                'Unable to verify VAT number using VIES API: ' . $fault->faultstring,
                ['checkVat' => $params],
            );
        }
    }

    public function getClient(): SoapClient
    {
        if (! $this->client instanceof SoapClient) {
            $this->client = new SoapClient(self::WSDL);
        }

        return $this->client;
    }
}
