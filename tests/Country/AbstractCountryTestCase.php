<?php

namespace Grizzlyware\Intl\Zones\Tests\Country;

use Grizzlyware\Intl\Zones\Tests\TestCase;
use Grizzlyware\Intl\Zones\Zones;

abstract class AbstractCountryTestCase extends TestCase
{
    abstract protected function getAlpha2CountryCode(): string;

    /**
     * The exact number of zones expected, for countries whose list is pinned by
     * resources/overrides. Return null for countries taken straight from
     * upstream, where the count moves every time the data is regenerated.
     */
    abstract protected function getExpectedTotalZones(): ?int;

    /**
     * The fewest zones a country should ever have. Catches the upstream data
     * thinning out without pinning a figure that has to be edited each time it
     * legitimately grows.
     */
    abstract protected function getMinimumTotalZones(): int;

    abstract protected function shouldHaveZoneCodes(): bool;

    public function testTotalZonesIsCorrect(): void
    {
        $zones = Zones::forAlpha2Code($this->getAlpha2CountryCode());

        if (null !== $expectedTotalZones = $this->getExpectedTotalZones()) {
            $this->assertCount(
                $expectedTotalZones,
                $zones,
            );

            return;
        }

        $this->assertGreaterThanOrEqual(
            $this->getMinimumTotalZones(),
            count($zones),
        );
    }

    public function testZoneCodes(): void
    {
        if (! $this->shouldHaveZoneCodes()) {
            $this->markTestSkipped("Country code does not use zone codes: " . $this->getAlpha2CountryCode());
        }

        $checkedOne = false;

        foreach (Zones::forAlpha2Code($this->getAlpha2CountryCode()) as $zone) {
            $this->assertIsString(
                $zone->code,
            );

            $checkedOne = true;
        }

        $this->assertTrue($checkedOne);
    }
}
