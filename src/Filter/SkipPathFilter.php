<?php

declare(strict_types=1);

namespace App\Filter;

use App\Dto\ClassDto;

/**
 * @extends \FilterIterator<int,ClassDto,\Iterator<int,ClassDto>>
 */
class SkipPathFilter extends \FilterIterator
{
    public function __construct(\Iterator $classIterator, private readonly string $path)
    {
        parent::__construct($classIterator);
    }

    public function accept(): bool
    {
        return !str_starts_with($this->current()->path, $this->path);
    }
}
