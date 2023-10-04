<?php

declare(strict_types=1);

namespace App;

use App\Dto\ClassDto;
use Composer\Autoload\ClassLoader;

/**
 * @implements \Iterator<int,ClassDto>
 */
final class ClassIterator implements \Iterator
{
    private int $position;

    /** @var ClassDto[] */
    private array $classMap;

    public function __construct(ClassLoader $loader)
    {
        $this->classMap = array_map(
            static fn ($name, $path): ClassDto => new ClassDto($name, (string) realpath($path)),
            array_keys($loader->getClassMap()),
            $loader->getClassMap()
        );
    }

    public function current(): ClassDto
    {
        return $this->classMap[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return $this->position < \count($this->classMap);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
