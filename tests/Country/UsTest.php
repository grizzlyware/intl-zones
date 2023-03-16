<?php

namespace Grizzlyware\Intl\Zones\Tests\Country;

class UsTest extends AbstractCountryTest
{
    protected function getAlpha2CountryCode(): string
    {
        return 'US';
    }

    protected function getExpectedTotalZones(): int
    {
        return 51;
    }
}
