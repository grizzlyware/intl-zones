#!/usr/bin/env php
<?php

/**
 * Compares two generated zone definition files and writes a human readable
 * summary of the differences to stdout.
 *
 * Exposes a "risk" verdict so scheduled regeneration can decide whether a
 * change is safe to merge unattended:
 *
 *   none     - nothing changed at all
 *   safe     - only additions
 *   review   - zones were removed or renamed, but every country still resolves
 *   breaking - a country disappeared entirely, so forAlpha2Code() will now throw
 *
 * Usage: summarise-zone-changes.php <before.php> <after.php>
 */

if ($argc !== 3) {
    fwrite(STDERR, "Usage: {$argv[0]} <before.php> <after.php>" . PHP_EOL);

    exit(2);
}

/**
 * @return array<string, array<int, string>> Country code => zone names
 */
$load = static function (string $path): array {
    if (! is_file($path)) {
        fwrite(STDERR, "Not a file: {$path}" . PHP_EOL);

        exit(2);
    }

    $data = require $path;

    if (! is_array($data)) {
        fwrite(STDERR, "Invalid data in: {$path}" . PHP_EOL);

        exit(2);
    }

    $countries = [];

    foreach ($data['countries'] ?? [] as $countryCode => $country) {
        $countries[$countryCode] = array_map(
            static fn (array|string $zone): string => is_string($zone) ? $zone : $zone['name'],
            $country['zones'] ?? [],
        );
    }

    ksort($countries);

    return $countries;
};

$before = $load($argv[1]);
$after = $load($argv[2]);

$removedCountries = array_keys(array_diff_key($before, $after));
$addedCountries = array_keys(array_diff_key($after, $before));

$zonesAdded = [];
$zonesRemoved = [];

foreach (array_intersect_key($before, $after) as $countryCode => $beforeZones) {
    $added = array_diff($after[$countryCode], $beforeZones);
    $removed = array_diff($beforeZones, $after[$countryCode]);

    if ([] !== $added) {
        $zonesAdded[$countryCode] = $added;
    }

    if ([] !== $removed) {
        $zonesRemoved[$countryCode] = $removed;
    }
}

$risk = match (true) {
    [] !== $removedCountries => 'breaking',
    [] !== $zonesRemoved => 'review',
    [] !== $addedCountries, [] !== $zonesAdded => 'safe',
    default => 'none',
};

$countZones = static fn (array $countries): int => array_sum(array_map('count', $countries));

$lines = [];

$lines[] = '| | Before | After |';
$lines[] = '| --- | ---: | ---: |';
$lines[] = sprintf('| Countries | %d | %d |', count($before), count($after));
$lines[] = sprintf('| Zones | %d | %d |', $countZones($before), $countZones($after));
$lines[] = '';

/**
 * Countries listed per zone section before the rest are elided. A pull request
 * body is capped at 65536 characters and a big upstream reshuffle can touch
 * every country at once.
 */
const MAX_COUNTRIES_LISTED = 40;

$section = static function (string $heading, array $countryCodes) use (&$lines): void {
    if ([] === $countryCodes) {
        return;
    }

    $lines[] = sprintf('### %s (%d)', $heading, count($countryCodes));
    $lines[] = '';
    $lines[] = '`' . implode('`, `', $countryCodes) . '`';
    $lines[] = '';
};

$section('Countries removed', $removedCountries);
$section('Countries added', $addedCountries);

$zoneSection = static function (string $heading, array $byCountry) use (&$lines): void {
    if ([] === $byCountry) {
        return;
    }

    $total = array_sum(array_map('count', $byCountry));

    $lines[] = sprintf('<details><summary>%s (%d across %d countries)</summary>', $heading, $total, count($byCountry));
    $lines[] = '';

    foreach (array_slice($byCountry, 0, MAX_COUNTRIES_LISTED, true) as $countryCode => $zones) {
        $lines[] = sprintf('- **%s** — %s', $countryCode, implode(', ', $zones));
    }

    if (count($byCountry) > MAX_COUNTRIES_LISTED) {
        $lines[] = sprintf(
            '- _…and %d more countries. Check out the branch to see the full diff._',
            count($byCountry) - MAX_COUNTRIES_LISTED,
        );
    }

    $lines[] = '';
    $lines[] = '</details>';
    $lines[] = '';
};

$zoneSection('Zones removed', $zonesRemoved);
$zoneSection('Zones added', $zonesAdded);

if ('breaking' === $risk) {
    $lines[] = '> [!CAUTION]';
    $lines[] = '> Countries were removed from the upstream data. `Zones::forAlpha2Code()` will now';
    $lines[] = '> throw a `MissingResourceException` for them. This needs a human and a major release.';
} elseif ('review' === $risk) {
    $lines[] = '> [!WARNING]';
    $lines[] = '> Zones were removed or renamed. Values already stored against the old names will no';
    $lines[] = '> longer match anything in this list.';
}

$summary = implode(PHP_EOL, $lines);

echo $summary . PHP_EOL;

if ($outputFile = getenv('GITHUB_OUTPUT')) {
    $delimiter = 'SUMMARY_' . bin2hex(random_bytes(8));

    file_put_contents($outputFile, implode(PHP_EOL, [
        'risk=' . $risk,
        'summary<<' . $delimiter,
        $summary,
        $delimiter,
        '',
    ]), FILE_APPEND);
}
