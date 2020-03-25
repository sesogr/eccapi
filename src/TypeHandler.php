<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

use JsonSerializable;

interface TypeHandler
{
    /**
     * Converts an internal representation of an item to a JSON-serializable representation.
     *
     * @param $item
     *
     * @return array|object|JsonSerializable
     */
    public function format($item);

    /**
     * Returns an array of internal representations for all items or those matching the search string, respectively.
     *
     * @param string|null $search
     *
     * @return array
     */
    public function list(?string $search): array;

    /**
     * Loads the internal representation for the item identified by the id.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function load(int $id);
}
