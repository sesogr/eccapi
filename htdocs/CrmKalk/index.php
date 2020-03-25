<?php declare(strict_types=1);
use DI\ContainerBuilder;
use SuiteCrmCalcApi\TypeHandler;
const ERROR_MISSING = 'Missing type parameter';
const ERROR_UNSUPPORTED = 'Unsupported type %s. Must be one of: %s';
const FILE_SETUP = __DIR__ . '/container-setup.php';
const PARAM_ID = 'id';
const PARAM_SEARCH = 'searchQuery';
const PARAM_TYPE = 'type';
const SLOT_HANDLERS = 'typeHandlers';
require_once __DIR__ . '/vendor/autoload.php';
try {
    if (!isset($_GET[PARAM_TYPE])) {
        throw new UnexpectedValueException(ERROR_MISSING);
    }
    $container = (new ContainerBuilder())->addDefinitions(FILE_SETUP)->build();
    $typeHandlers = $container->get(SLOT_HANDLERS);
    if (!isset($typeHandlers[$_GET[PARAM_TYPE]])) {
        throw new UnexpectedValueException(
            sprintf(ERROR_UNSUPPORTED, $_GET[PARAM_TYPE], implode(', ', array_keys($typeHandlers)))
        );
    }
    /** @var TypeHandler $handler */
    $handler = $container->get($typeHandlers[$_GET[PARAM_TYPE]]);
    $result = isset($_GET[PARAM_ID])
        ? $handler->format($handler->load($_GET[PARAM_ID]))
        : array_map([$handler, 'format'], $handler->list($_GET[PARAM_SEARCH] ?? null));
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
} catch (Throwable $t) {
    header("Content-Type: text/plain; charset=UTF-8", true, 500);
    echo $t;
}