# Changelog

All notable changes to `intl-zones` will be documented in this file.

## V1.3.0 - 2026-08-24

Zone definitions regenerated from upstream.

Countries: 249 → 200. Zones: 3,690 → 4,913.

Breaking: 49 countries no longer resolve, and `Zones::forAlpha2Code()` throws a `MissingResourceException` for them — `AI`, `AQ`, `AS`, `AW`, `AX`, `BL`, `BM`, `BV`, `CC`, `CK`, `CW`, `CX`, `EH`, `FK`, `FO`, `GF`, `GG`, `GI`, `GP`, `GS`, `GU`, `HK`, `HM`, `IM`, `IO`, `JE`, `KY`, `MF`, `MO`, `MP`, `MQ`, `MS`, `NC`, `NF`, `NU`, `PF`, `PM`, `PN`, `PR`, `RE`, `SJ`, `SX`, `TC`, `TF`, `TK`, `VA`, `VG`, `VI`, `YT`.

Zones removed or renamed: 1065 across 130 countries. Values stored against the old names will no longer match.

Most of the renames are diacritics being restored upstream — `Cordoba` → `Córdoba`, `Sao Paulo` → `São Paulo`, `Karnten` → `Kärnten` — which accounts for 892 of the 1,065. The remaining 173 are genuine changes. Some countries also moved down a level: `CZ` 14 → 90, `EE` 15 → 94, `BD` 7 → 72.

The removed territories were not dropped by upstream, but moved under their parent country: `US` 51 → 57, `CN` 31 → 34.

Regeneration now runs weekly and publishes automatically.

## V1.2.1 - 2023-03-17

Fix: Mark the Generator class as internal

## V1.2 - 2023-03-17

All countries have been added in this release, sourced from: https://github.com/stefangabos/world_countries

## V1.1 - 2023-03-16

Support regional locales

## V1.0 - 2023-03-16

Initial release
