<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

use Faker\Generator;

abstract class FakeDataHandler implements TypeHandler
{
    protected $generator;

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    public function format($item)
    {
        return $item;
    }
}
