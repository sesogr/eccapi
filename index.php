<?php declare(strict_types=1);
use DI\ContainerBuilder;
use Intervention\HttpAuth\Exception\AuthentificationException;
use SuiteCrmCalcApi\TypeHandler;
const ERROR_MISSING = 'Missing type parameter';
const ERROR_UNSUPPORTED = 'Unsupported type %s. Must be one of: %s';
const FILE_AUTH = __DIR__ . '/digest-auth.php';
const FILE_SETUP = __DIR__ . '/container-setup.php';
const PARAM_ID = 'id';
const PARAM_SEARCH = 'searchQuery';
const PARAM_TYPE = 'type';
const PARAM_CHILDREN = 'customersByParentID';
const PARAM_CONSUMERS = 'consumersByCustomerID';
const SLOT_HANDLERS = 'typeHandlers';
require_once __DIR__ . '/vendor/autoload.php';
try {
    [$clientId, $secret] = include FILE_AUTH;
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
        ? $handler->format($handler->load(intval($_GET[PARAM_ID])))
        : array_map([$handler, 'format'], $handler->list($_GET[PARAM_SEARCH] ?? null));
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
} catch (AuthentificationException $e) {
    header("Content-Type: text/plain; charset=UTF-8", true);
    echo $e->getMessage();
} catch (Throwable $t) {
    header("Content-Type: text/plain; charset=UTF-8", true, 500);
    echo $t;
}
