<?php declare(strict_types=1);
use Faker\Factory;
use Faker\Generator;
use SuiteCrmCalcApi\FakeEvuHandler;
use function DI\factory;

return [
    Generator::class => factory(function () {
        return Factory::create('de_DE');
    }),
    'typeHandlers' => [
        'evu' => FakeEvuHandler::class,
    ],
];
