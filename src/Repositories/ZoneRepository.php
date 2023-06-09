<?php

namespace Grizzlyware\Intl\Zones\Repositories;

use Grizzlyware\Intl\Zones\Entities\Locale;
use Grizzlyware\Intl\Zones\Entities\Zone;
use Grizzlyware\Intl\Zones\Exceptions\MissingResourceException;

/**
 * @internal
 */
class ZoneRepository
{
    private Locale $locale;
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

        $filePaths = [
            $this->dataPath . '/zones/' . $this->locale->getFallbackLanguage() . '.php',
        ];

        if ($this->locale->getFallbackLanguage() !== (string)$this->locale) {
            $filePaths[] = $this->dataPath . '/zones/' . $this->locale . '.php';
        }

        $countries = [];

        foreach ($filePaths as $filePath) {
            if (! file_exists($filePath)) {
                continue;
            }

            $data = require($filePath);

            if (! is_array($data)) {
                throw new \Exception("Invalid data found in {$filePath}");
            }

            $countries = array_merge(
                $countries,
                $data['countries'] ?? [],
            );
        }

        $this->countries = $countries;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = new Locale($locale);
        unset($this->countries);
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
