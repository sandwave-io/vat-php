<?php declare(strict_types = 1);

namespace SandwaveIo\Vat;
use SoapClient;

// TODO: set to final and add an interface and mock that.
class ViesClient
{
    const VIES_WSDL_URI = 'https://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';
    // TODO: check if we need any options. timeout? Soap version?
    const SOAP_OPTIONS = [];

    private SoapClient $viesClient;

    public function __construct()
    {
        // TODO: client to seperate class.
        $this->viesClient = new SoapClient(self::VIES_WSDL_URI, self::SOAP_OPTIONS);
    }

    public function checkVat(string $vatNumber, string $countryCode)
    {
        $params = [
            'countryCode' => $countryCode,
            'vatNumber' => $vatNumber,
        ];
        // TODO: do we need a try catch? Maybe for connection timeout etc?
        $response = $this->viesClient->checkVat($params);
        return $response->valid;
    }
}
