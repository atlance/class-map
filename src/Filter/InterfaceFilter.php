<?php

declare(strict_types=1);

namespace App\Filter;

use App\Dto\ClassDto;

/**
 * @extends \FilterIterator<int,ClassDto,\Iterator<int,ClassDto>>
 */
class InterfaceFilter extends \FilterIterator
{
    public function __construct(\Iterator $classIterator, private readonly string $interface)
    {
        parent::__construct($classIterator);
    }

    public function accept(): bool
    {
        try {
            /** @var ClassDto $class */
            $class = $this->current();
            if (!class_exists($class->name) || !$interfaces = class_implements($class->name)) {
                return false;
            }

            return \in_array($this->interface, $interfaces, true);
        } catch (\Throwable) {
            return false;
        }
    }
}
