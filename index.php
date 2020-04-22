<?php declare(strict_types=1);
use DI\ContainerBuilder;
use Intervention\HttpAuth\Exception\AuthentificationException;
use SuiteCrmCalcApi\TypeHandler;
const ERROR_MISSING = 'Missing type parameter';
const ERROR_UNSUPPORTED = 'Unsupported type %s. Must be one of: %s';
const FILE_AUTH = __DIR__ . '/digest-auth.php';
const FILE_SETUP = __DIR__ . '/container-setup.php';
const PARAM_CHILDREN = 'customersByParentID';
const PARAM_CONSUMERS = 'consumersByCustomerID';
const PARAM_EXTENDED = 'extended';
const PARAM_ID = 'id';
const PARAM_SEARCH = 'searchQuery';
const PARAM_TYPE = 'type';
const SLOT_HANDLERS = 'typeHandlers';
require_once __DIR__ . '/vendor/autoload.php';
try {
    header("Access-Control-Allow-Origin: *");
    [$clientId, $secret] = include FILE_AUTH;
    $type = $_GET[PARAM_TYPE] ?? null;
    $listMethod = 'list';
    $listArgument = $_GET[PARAM_SEARCH] ?? null;
    $isExtended = in_array(strtolower($_GET[PARAM_EXTENDED] ?? ''), ['1', 'on', 't', 'true', 'y', 'yes']);
    if (isset($_GET[PARAM_CHILDREN])) {
        $type = PARAM_CHILDREN;
        $listMethod = 'listChildren';
        $listArgument = $_GET[PARAM_CHILDREN];
    } elseif (isset($_GET[PARAM_CONSUMERS])) {
        $type = PARAM_CONSUMERS;
        $listMethod = 'listConsumers';
        $listArgument = $_GET[PARAM_CONSUMERS];
    } elseif (!isset($type)) {
        throw new UnexpectedValueException(ERROR_MISSING);
    }
    $container = (new ContainerBuilder())->addDefinitions(FILE_SETUP)->build();
    $typeHandlers = $container->get(SLOT_HANDLERS);
    if (!isset($typeHandlers[$type])) {
        throw new UnexpectedValueException(
            sprintf(ERROR_UNSUPPORTED, $type, implode(', ', array_keys($typeHandlers)))
        );
    }
    /** @var TypeHandler $handler */
    $handler = $container->get($typeHandlers[$type]);
    $result = isset($_GET[PARAM_ID])
        ? $handler->format($handler->load($_GET[PARAM_ID], $isExtended))
        : array_map([$handler, 'format'], $handler->$listMethod($listArgument, $isExtended));
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
} catch (AuthentificationException $e) {
    header("Content-Type: text/plain; charset=UTF-8", true);
    echo $e->getMessage();
} catch (Throwable $t) {
    header("Content-Type: text/plain; charset=UTF-8", true, 500);
    echo $t;
}
