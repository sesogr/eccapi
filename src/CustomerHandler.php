<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

interface CustomerHandler
{
    /**
     * Returns an array of internal representations for all customers whose parent ID matches the given one.
     *
     * @param string $parentId
     *
     * @return array
     */
    public function listChildren(string $parentId): array;

    /**
     * Returns an array of internal representations for all consumers whose customer ID matches the given one.
     *
     * @param string $ownerId
     *
     * @return array
     */
    public function listConsumers(string $ownerId): array;
}
