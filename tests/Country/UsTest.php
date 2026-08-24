<?php

namespace Grizzlyware\Intl\Zones\Tests\Country;

class UsTest extends AbstractCountryTestCase
{
    protected function getAlpha2CountryCode(): string
    {
        return 'US';
    }

    protected function getExpectedTotalZones(): ?int
    {
        // Sourced from upstream, which moves states and territories in and out.
        return null;
    }

    protected function getMinimumTotalZones(): int
    {
        return 50;
    }

    protected function shouldHaveZoneCodes(): bool
    {
        return true;
    }
}
