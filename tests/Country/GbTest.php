<?php

namespace Grizzlyware\Intl\Zones\Tests\Country;

use Grizzlyware\Intl\Zones\Entities\Zone;
use Grizzlyware\Intl\Zones\Zones;

class GbTest extends AbstractCountryTestCase
{
    protected function getAlpha2CountryCode(): string
    {
        return 'GB';
    }

    protected function getExpectedTotalZones(): ?int
    {
        // Pinned by resources/overrides/zones/en.php, so an exact count is safe.
        return 100;
    }

    protected function getMinimumTotalZones(): int
    {
        return 100;
    }

    protected function shouldHaveZoneCodes(): bool
    {
        return false;
    }

    /**
     * The United Kingdom is four countries, and the list has to cover all of
     * them. Northern Ireland was missing entirely, and England was six of its
     * ceremonial counties short.
     */
    public function testItCoversEveryPartOfTheUnitedKingdom(): void
    {
        $names = array_map(
            static fn (Zone $zone): string => $zone->name,
            Zones::forAlpha2Code($this->getAlpha2CountryCode()),
        );

        foreach ([
            'Berkshire',
            'Bristol',
            'East Riding of Yorkshire',
            'Herefordshire',
            'Isle of Wight',
            'Rutland',
            'Antrim',
            'Armagh',
            'Down',
            'Fermanagh',
            'Londonderry',
            'Tyrone',
            'Glamorgan',
            'Perthshire',
        ] as $county) {
            $this->assertContains($county, $names);
        }
    }
}
