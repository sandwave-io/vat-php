<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\VatRates;

use DateTime;
use SoapClient;
use SoapFault;

final class TaxesEuropeDatabaseClient implements ResolvesVatRates
{
    const WSDL = 'https://ec.europa.eu/taxation_customs/tedb/ws/VatRetrievalService.wsdl';
    const SOAP_CONFIG = [
        'version' => SOAP_1_1,
        'cache_wsdl' => WSDL_CACHE_MEMORY,
    ];
    /* @see https://ec.europa.eu/taxation_customs/tedb/ws/VatRetrievalServiceType.xsd */
    const RATE_TYPE_STANDARD = 'STANDARD';
    const RATE_VALUE_TYPE_DEFAULT = 'DEFAULT';

    private SoapClient $client;

    public function __construct(?SoapClient $client = null)
    {
        $this->client = $client ?? new SoapClient(self::WSDL, self::SOAP_CONFIG);
    }

    /**
     * Retrieve the default VAT rate for a given country. If the countries VAT rate cannot be resolved, null is returned.
     * This also happens if the country does not exist in the European Tax Database.
     *
     * @param string        $countryCode ISO country code
     * @param DateTime|null $dateTime    Date of checking, defaults to today.
     *
     * @return float|null
     */
    public function getDefaultVatRateForCountry(string $countryCode, ?DateTime $dateTime = null): ?float
    {
        $date = $dateTime ?? new DateTime();
        $response = $this->call('retrieveVatRates', [
            'retrieveVatRatesRequest' => [
                'memberStates' => [$countryCode],
                'situationOn' => $date->format('Y-m-d'),
            ],
        ]);

        if (is_null($response)) {
            return null;
        }

        return $this->parseRetrieveVatRateResponse($response, $countryCode, self::RATE_TYPE_STANDARD, self::RATE_VALUE_TYPE_DEFAULT);
    }

    private function parseRetrieveVatRateResponse(object $response, string $countryCode, string $rateType, string $rateValueType): ?float
    {
        if (! isset($response->vatRateResults)) {
            return null;
        }
        foreach ($response->vatRateResults as $vatRateResult) {
            if (
                isset($vatRateResult->memberState) &&
                $vatRateResult->memberState === $countryCode &&
                isset($vatRateResult->type) &&
                $vatRateResult->type === $rateType &&
                isset($vatRateResult->rate) &&
                isset($vatRateResult->rate->type) &&
                isset($vatRateResult->rate->value) &&
                $vatRateResult->rate->type === $rateValueType
            ) {
                return $vatRateResult->rate->value;
            }
        }
        return null;
    }

    /**
     * @param string              $call
     * @param array<string,array> $params
     *
     * @return object|null
     */
    private function call(string $call, array $params): ?object
    {
        try {
            $response = $this->client->__soapCall($call, $params);
            if (! is_object($response)) {
                return null;
            }
            return $response;
        } catch (SoapFault $fault) {
            return null;
        }
    }
}
