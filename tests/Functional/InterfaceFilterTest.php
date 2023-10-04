<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\ClassIterator;
use App\Dto\ClassDto;
use App\Filter\InterfaceFilter;
use App\Filter\SkipPathFilter;
use Composer\Autoload\ClassLoader;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class InterfaceFilterTest extends TestCase
{
    private ?ClassLoader $loader = null;

    private static string $vendorDir;

    private static ClassLoader $staticLoader;

    #[DataProvider('dataset')]
    public function test(string $interface, array $expectedClasses): void
    {
        self::assertEquals(
            [],
            array_diff(
                $expectedClasses,
                iterator_to_array(
                    new InterfaceFilter(
                        new SkipPathFilter(
                            new ClassIterator($this->loader()),
                            self::$vendorDir
                        ),
                        $interface
                    )
                )
            )
        );
    }

    public function loader(): ClassLoader
    {
        if (!$this->loader instanceof ClassLoader) {
            throw new \RuntimeException('loader MUST be initialized');
        }

        return $this->loader;
    }

    public static function dataset(): \Generator
    {
        yield 'classes implemented \Iterator' => [
            \Iterator::class,
            [
                InterfaceFilter::class,
                SkipPathFilter::class,
                ClassIterator::class,
            ],
        ];
        yield 'classes implemented \Stringable' => [\Stringable::class, [ClassDto::class]];
    }

    public static function setUpBeforeClass(): void
    {
        self::$vendorDir = \dirname(__DIR__, 2) . \DIRECTORY_SEPARATOR . 'vendor';
        self::$staticLoader = require self::$vendorDir . \DIRECTORY_SEPARATOR . 'autoload.php';
    }

    protected function setUp(): void
    {
        $this->loader = self::$staticLoader;
    }

    protected function tearDown(): void
    {
        $this->loader = null;
    }
}
