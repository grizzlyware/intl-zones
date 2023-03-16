<?php

namespace Grizzlyware\Intl\Zones\Entities;

/**
 * @internal
 */
class Locale implements \Stringable
{
    private array $parts;

    public function __construct(private string $locale)
    {
        if (! preg_match('/^[a-z_]{2,5}$/i', $this->locale)) {
            throw new \InvalidArgumentException("\Locale is invalid");
        }

        $this->parts = explode('_', $this->locale);
    }

    public function getFallbackLanguage(): string
    {
        return $this->parts[0];
    }

    public function __toString()
    {
        return $this->locale;
    }
}
