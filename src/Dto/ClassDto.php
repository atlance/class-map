<?php

declare(strict_types=1);

namespace App\Dto;

class ClassDto implements \Stringable
{
    public function __construct(public string $name, public string $path)
    {
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
