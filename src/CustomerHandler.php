<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

interface CustomerHandler
{
    /**
     * Returns an array of internal representations for all customers whose parent ID matches the given one.
     *
     * @param string $parentId
     * @param bool $isExtended
     *
     * @return array
     */
    public function listChildren(string $parentId, bool $isExtended = false): array;

    /**
     * Returns an array of internal representations for all consumers whose customer ID matches the given one.
     *
     * @param string $ownerId
     * @param bool $isExtended
     *
     * @return array
     */
    public function listConsumers(string $ownerId, bool $isExtended = false): array;
}
