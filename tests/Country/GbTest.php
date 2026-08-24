<?php

namespace Grizzlyware\Intl\Zones\Tests\Country;

class GbTest extends AbstractCountryTestCase
{
    protected function getAlpha2CountryCode(): string
    {
        return 'GB';
    }

    protected function getExpectedTotalZones(): int
    {
        return 88;
    }

    protected function shouldHaveZoneCodes(): bool
    {
        return false;
    }
}
