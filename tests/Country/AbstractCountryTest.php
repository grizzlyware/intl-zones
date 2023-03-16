<?php

namespace Grizzlyware\Intl\Zones\Tests\Country;

use Grizzlyware\Intl\Zones\Tests\TestCase;
use Grizzlyware\Intl\Zones\Zones;

abstract class AbstractCountryTest extends TestCase
{
    abstract protected function getAlpha2CountryCode(): string;

    abstract protected function getExpectedTotalZones(): int;

    abstract protected function shouldHaveZoneCodes(): bool;

    public function testTotalZonesIsCorrect(): void
    {
        $this->assertCount(
            $this->getExpectedTotalZones(),
            Zones::forAlpha2Code($this->getAlpha2CountryCode()),
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
