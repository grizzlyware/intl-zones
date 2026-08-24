<?php

namespace Grizzlyware\Intl\Zones\Tests\Country;

class GbTest extends AbstractCountryTestCase
{
    protected function getAlpha2CountryCode(): string
    {
        return 'GB';
    }

    protected function getExpectedTotalZones(): ?int
    {
        // Pinned by resources/overrides/zones/en.php, so an exact count is safe.
        return 88;
    }

    protected function getMinimumTotalZones(): int
    {
        return 88;
    }

    protected function shouldHaveZoneCodes(): bool
    {
        return false;
    }
}
