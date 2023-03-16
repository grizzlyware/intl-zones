<?php

namespace Grizzlyware\Intl\Zones;

use Grizzlyware\Intl\Zones\Collections\ZoneCollection;
use Grizzlyware\Intl\Zones\Entities\Zone;
use Grizzlyware\Intl\Zones\Exceptions\MissingResourceException;
use Grizzlyware\Intl\Zones\Repositories\ZoneRepository;

class Zones
{
    private static ZoneRepository $repository;

    /**
     * @throws MissingResourceException
     * @return array<int, Zone>
     */
    public static function forAlpha2Code(string $alpha2Code): array
    {
        return self::getRepository()
            ->getZonesForAlpha2CountryCode($alpha2Code)
        ;
    }

    private static function getRepository(): ZoneRepository
    {
        if (! isset(self::$repository)) {
            self::$repository = self::makeRepository();
        }

        return self::$repository;
    }

    public static function setLocale(string $locale): void
    {
        self::getRepository()
            ->setLocale($locale)
        ;
    }

    private static function makeRepository(): ZoneRepository
    {
        return new ZoneRepository(
            __DIR__ . '/../resources/data',
        );
    }
}
