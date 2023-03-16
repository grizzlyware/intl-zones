<?php

namespace Grizzlyware\Intl\Zones\Tests\Country;

use Grizzlyware\Intl\Zones\Tests\TestCase;
use Grizzlyware\Intl\Zones\Zones;

abstract class AbstractCountryTest extends TestCase
{
    abstract protected function getAlpha2CountryCode(): string;

    abstract protected function getExpectedTotalZones(): int;

    public function testTotalZonesIsCorrect(): void
    {
        $this->assertCount(
            $this->getExpectedTotalZones(),
            Zones::forAlpha2Code($this->getAlpha2CountryCode()),
        );
    }
}
