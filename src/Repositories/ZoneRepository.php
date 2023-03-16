<?php

namespace Grizzlyware\Intl\Zones\Repositories;

use Grizzlyware\Intl\Zones\Entities\Zone;
use Grizzlyware\Intl\Zones\Exceptions\MissingResourceException;

/**
 * @internal
 */
class ZoneRepository
{
    private string $locale;
    private array $countries;
    private string $dataPath;

    public function __construct(string $dataPath)
    {
        $this->setLocale('en');

        $dataPath = realpath($dataPath);

        if (false === $dataPath) {
            throw new \InvalidArgumentException("\$dataPath is not resolvable to a filesystem path");
        }

        $this->dataPath = $dataPath;
    }

    private function loadData(): void
    {
        if (isset($this->countries)) {
            return;
        }

        $filePath = $this->dataPath . '/zones/' . $this->locale . '.php';

        if (! file_exists($filePath)) {
            throw new MissingResourceException("Could not load zones for locale '{$this->locale}': {$filePath}");
        }

        $data = require($filePath);

        if (! is_array($data)) {
            throw new \Exception("Invalid data found in {$filePath}");
        }

        $this->countries = $data['countries'] ?? [];
    }

    public function setLocale(string $locale): void
    {
        if (! preg_match('/^[a-z_]{2,5}$/i', $locale)) {
            throw new \InvalidArgumentException("\$locale is invalid");
        }

        unset($this->countries);
        $this->locale = $locale;
    }

    /**
     * @return array<int, Zone>
     * @throws MissingResourceException
     */
    public function getZonesForAlpha2CountryCode(string $alpha2Code): array
    {
        $alpha2Code = trim(strtoupper($alpha2Code));

        $this->loadData();

        if (! isset($this->countries[$alpha2Code])) {
            throw new MissingResourceException("No country definition for '{$alpha2Code}'");
        }

        return array_map(function (array|string $zone): Zone {
            /**
             * Allow just a list of zones to be defined when only the name is useful/relevant
             */
            if (is_string($zone)) {
                $zone = [
                    'name' => $zone,
                ];
            }

            return new Zone(
                name: $zone['name'],
                code: $zone['code'] ?? null
            );
        }, $this->countries[$alpha2Code]['zones'] ?? []);
    }
}
