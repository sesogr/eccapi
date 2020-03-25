<?php declare(strict_types=1);
use Faker\Factory;
use Faker\Generator;
use SuiteCrmCalcApi\FakeConsumerHandler;
use SuiteCrmCalcApi\FakeCustomerHandler;
use SuiteCrmCalcApi\FakeEvuHandler;
use SuiteCrmCalcApi\FakeGridProviderHandler;
use function DI\factory;

return [
    Generator::class => factory(function () {
        return Factory::create('de_DE');
    }),
    'typeHandlers' => [
        'consumer' => FakeConsumerHandler::class,
        'customer' => FakeCustomerHandler::class,
        'evu' => FakeEvuHandler::class,
        'gridProvider' => FakeGridProviderHandler::class,
    ],
];
