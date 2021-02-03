<?php
namespace SandwaveIo\Vat\VatRates;

use DateTime;
use SoapClient;

final class TaxesEuropeDatabaseClient
{
    const WSDL = "https://ec.europa.eu/taxation_customs/tedb/ws/VatRetrievalService.wsdl";
    /* @see https://ec.europa.eu/taxation_customs/tedb/ws/VatRetrievalServiceMessage.xsd */
    /* @see https://ec.europa.eu/taxation_customs/tedb/ws/VatRetrievalServiceType.xsd */

//    TODO: Add options to Soap client, such as timeout and caching of the WSDL.
//    TODO: Specify SOAP version 1.1 @see https://stackoverflow.com/questions/736845/can-a-wsdl-indicate-the-soap-version-1-1-or-1-2-of-the-web-service

    private SoapClient $client;

    public function __construct(?SoapClient $client = null)
    {
        $this->client = $client ?? new SoapClient(self::WSDL);
    }

    public function getRates(string $countryCode, DateTime $dateTime)
    {
        var_dump($dateTime->format('YYYY-mm-dd'));
//        return $this->client->__soapCall("retrieveVatRates", [
//            'retrieveVatRatesReqMsg' => [
//                'memberStates' => [$countryCode],
//                'situationOn' => '2021-02-03',
//            ],
//        ]);
    }
}