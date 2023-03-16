<?php

namespace Grizzlyware\Intl\Zones\Entities;

readonly class Zone
{
    /**
     * @internal
     */
    public function __construct(public string $name, public ?string $code)
    {
        //
    }
}
