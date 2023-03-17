<?php

namespace Grizzlyware\Intl\Zones\Generator;

class Generator
{
    /**
     * @var array[]
     */
    private array $overrides;

    public function __construct(
        private string $zonesPath,
        private string $outputFile,
    ) {
        //
    }

    public function setOverrides(array ...$overrides): void
    {
        $this->overrides = $overrides;
    }

    private function loadData(): array
    {
        return json_decode(
            file_get_contents($this->zonesPath),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
    }

    public function generate(): void
    {
        $countries = [];

        foreach ($this->loadData() as $zone) {
            if (! isset($countries[$zone['country']])) {
                $countries[$zone['country']] = [
                    'zones' => [],
                ];
            }

            $code = explode('-', $zone['code'], 2)[1] ?? null;

            if (preg_match('/^XX-\d+$/', $code)) {
                $code = null;
            }

            $countries[$zone['country']]['zones'][] = [
                'code' => $code,
                'name' => $zone['name'],
            ];
        }

        $output = [
            'countries' => $countries,
        ];

        foreach ($this->overrides as $override) {
            foreach ($override['countries'] ?? [] as $countryCode => $country) {
                unset($output['countries'][$countryCode]);
            }
        }

        $output = array_merge_recursive($output, ...$this->overrides);

        foreach ($output['countries'] as $countryCode => $country) {
            $codes = [];

            foreach ($country['zones'] as $zone) {
                if (is_string($zone) || ! isset($zone['code']) || null === $zone['code']) {
                    continue;
                }

                $codes[] = $zone['code'];
            }

            if (count(array_unique($codes)) === count($country['zones'])) {
                continue;
            }

            foreach ($country['zones'] as $zoneKey => $zone) {
                if (is_string($output['countries'][$countryCode]['zones'][$zoneKey])) {
                    continue;
                }

                $output['countries'][$countryCode]['zones'][$zoneKey]['code'] = null;
            }
        }

        $output = var_export($output, true);

        $outputPhp = <<<EOF
<?php

##### WARNING #####
# This file is auto generated. Do not edit it!
# Edit override files or fix the source of this file: {$this->zonesPath}
##### WARNING #####

return {$output};

EOF;

        file_put_contents(
            $this->outputFile,
            $outputPhp
        );
    }
}
