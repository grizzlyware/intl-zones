<?php

namespace Grizzlyware\Intl\Zones\Tests\Feature;

use Grizzlyware\Intl\Zones\Entities\Zone;
use Grizzlyware\Intl\Zones\Tests\TestCase;
use Grizzlyware\Intl\Zones\Zones;

class ZonesTest extends TestCase
{
    public function testCannotSetInvalidLocaleWithDirectoryTraversal(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("\$locale is invalid");

        Zones::setLocale('../en');
    }

    public function testCannotSetShortInvalidLocale(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("\$locale is invalid");

        Zones::setLocale('e');
    }

    public function testCanGetZonesForGb(): void
    {
        $zones = Zones::forAlpha2Code('GB');

        $this->assertIsArray($zones);

        $this->assertContainsOnlyInstancesOf(
            Zone::class,
            $zones,
        );

        $foundZone = false;

        foreach ($zones as $zone) {
            if ($zone->name === 'Cornwall') {
                $foundZone = true;
            }
        }

        $this->assertTrue($foundZone);
    }

    public function testUsStatesNotReturnedInGb(): void
    {
        $zones = Zones::forAlpha2Code('GB');

        $foundZone = false;

        foreach ($zones as $zone) {
            if ($zone->name === 'Florida') {
                $foundZone = true;
            }
        }

        $this->assertFalse($foundZone);
    }

    public function testCodeSetOnUsStateFlorida(): void
    {
        $zones = Zones::forAlpha2Code('US');

        $foundZone = false;

        foreach ($zones as $zone) {
            if ($zone->name === 'Florida') {
                $foundZone = true;

                $this->assertEquals(
                    'FL',
                    $zone->code,
                );
            }
        }

        $this->assertTrue($foundZone);
    }

    public function testCodeIsNullOnGbZones(): void
    {
        $zones = Zones::forAlpha2Code('GB');

        $zonesChecked = 0;

        foreach ($zones as $zone) {
            $this->assertNull(
                $zone->code,
            );

            $zonesChecked++;
        }

        $this->assertGreaterThan(
            0,
            count($zones)
        );

        $this->assertEquals(
            count($zones),
            $zonesChecked
        );
    }

    public function testGbCountiesNotReturnedInUs(): void
    {
        $zones = Zones::forAlpha2Code('US');

        $foundZone = false;

        foreach ($zones as $zone) {
            if ($zone->name === 'Cornwall') {
                $foundZone = true;
            }
        }

        $this->assertFalse($foundZone);
    }

    public function testCanGetZonesWithLowercaseCode(): void
    {
        $zones = Zones::forAlpha2Code('GB');

        $this->assertIsArray($zones);

        $this->assertContainsOnlyInstancesOf(
            Zone::class,
            $zones,
        );

        $foundZone = false;

        foreach ($zones as $zone) {
            if ($zone->name === 'Cornwall') {
                $foundZone = true;
            }
        }

        $this->assertTrue($foundZone);
    }
}
