# Provides a list of zones for countries

[![Latest Version on Packagist](https://img.shields.io/packagist/v/grizzlyware/intl-zones.svg?style=flat-square)](https://packagist.org/packages/grizzlyware/intl-zones)
[![Tests](https://img.shields.io/github/actions/workflow/status/grizzlyware/intl-zones/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/grizzlyware/intl-zones/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/grizzlyware/intl-zones.svg?style=flat-square)](https://packagist.org/packages/grizzlyware/intl-zones)

This package provides a list of zones for a particular country.

## Installation

You can install the package via composer:

```bash
composer require grizzlyware/intl-zones
```

## Usage

```php
use Grizzlyware\Intl\Zones\Zones;

$zones = Zones::forAlpha2Code('GB');

foreach ($zones as $zone) {
    echo "The name is: {$zone->name}" . PHP_EOL;
    
    if (null !== $zone->code) {
        echo "The code is: {$zone->code}" . PHP_EOL;
    } else {
        echo "No code available" . PHP_EOL;
    }
}
```

## Testing

```bash
composer test
```

## Contributing

Please open a PR with the additional zones and supported countries. Tests should be added where appropriate.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please do not publicly disclose any vulnerabilities - Report any vulnerabilities to contact@grizzlyware.com directly

## Credits

- [Grizzlyware Ltd](https://github.com/grizzlyware)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
