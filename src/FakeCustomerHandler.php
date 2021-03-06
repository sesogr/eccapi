<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

class FakeCustomerHandler extends FakeDataHandler implements CustomerHandler
{
    public function list(?string $search, bool $isExtended = false): array
    {
        return array_map(
            function () use ($search, $isExtended) {
                return $this->load($this->generator->uuid, $isExtended);
            },
            range(0, $this->generator->biasedNumberBetween(2, 20))
        );
    }

    public function listChildren(string $parentId, bool $isExtended = false): array
    {
        return array_map(
            function ($item) use ($parentId) {
                return ['kundeID' => $parentId] + $item;
            },
            $this->generator->optional()->passthrough(
                (new FakeCustomerHandler($this->generator))->list($this->generator->word, $isExtended)
            ) ?: []
        );
    }

    public function listConsumers(string $ownerId, bool $isExtended = false): array
    {
        return array_map(
            function ($item) use ($ownerId) {
                return ['kundeID' => $ownerId] + $item;
            },
            $this->generator->optional()->passthrough(
                (new FakeConsumerHandler($this->generator))->list($this->generator->word, $isExtended)
            ) ?: []
        );
    }

    public function load(string $id, bool $isExtended = false)
    {
        $gen = $this->generator;
        $consumers = $isExtended ? ['consumers' => $this->listConsumers($id)] : [];
        return [
            'id' => $id,
            'kundeName' => $gen->company . ($gen->boolean ? ', ' . $gen->city : ''),
            'kundeNummer' => $gen->ean13,
            'verantwortlicherID' => $gen->uuid,
            'rechnungsanschriftVerwenden' => $gen->boolean,
            'createdOn' => $this->generator->date(DATE_ATOM),
            'kundeID' => $id,
            'consumerCount' => $isExtended ? count($consumers) : $gen->biasedNumberBetween(0, 100),
            'addresses' => $gen->optional()->passthrough((new FakeAddressHandler($this->generator))->list(null)) ?: [],
        ] + $consumers;
    }
}
