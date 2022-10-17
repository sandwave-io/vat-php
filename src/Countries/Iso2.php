<?php declare(strict_types = 1);

namespace SandwaveIo\Vat\Countries;

use DivineOmega\Countries\Countries;

final class Iso2 implements ResolvesCountries
{
    public static Countries $countries;
    public static string $europe = "Europe";

    function __construct() {
        self::$countries = new Countries;
    }

    public function isCountryValid(string $countryCode): bool
    {
        $country = self::$countries->getByIsoCode($countryCode);

        return isset($country);
    }

    public function isCountryInEu(string $countryCode): bool
    {
        $country = self::$countries->getByIsoCode($countryCode);

        return $country && $country->region == self::$europe;
    }
}
