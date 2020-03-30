<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

class FakeCustomerHandler extends FakeDataHandler implements CustomerHandler
{
    public function list(?string $search): array
    {
        return array_map(
            function () use ($search) {
                return $this->load($this->generator->randomNumber(8));
            },
            range(0, $this->generator->biasedNumberBetween(2, 20))
        );
    }

    public function listChildren(string $parentId): array
    {
        return array_map(
            function ($item) use ($parentId) {
                return ['kundeID' => $parentId] + $item;
            },
            $this->generator->optional()->passthrough(
                (new FakeCustomerHandler($this->generator))->list($this->generator->word)
            ) ?: []
        );
    }

    public function listConsumers(string $ownerId): array
    {
        return array_map(
            function ($item) use ($ownerId) {
                return ['kundeID' => $ownerId] + $item;
            },
            $this->generator->optional()->passthrough(
                (new FakeConsumerHandler($this->generator))->list($this->generator->word)
            ) ?: []
        );
    }

    public function load(int $id)
    {
        $gen = $this->generator;
        return [
            'id' => $id,
            'kundeName' => $gen->company . ($gen->boolean ? ', ' . $gen->city : ''),
            'kundeNummer' => $gen->ean13,
            'verantwortlicherID' => $gen->randomNumber(8),
            'rechnungsanschriftVerwenden' => $gen->boolean,
            'createdOn' => $this->generator->date(DATE_ATOM),
            'kundeID' => $gen->optional()->randomNumber(8),
            'addresses' => $gen->optional()->passthrough((new FakeAddressHandler($this->generator))->list(null)) ?: [],
        ];
    }
}
