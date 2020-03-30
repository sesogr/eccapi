<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

class FakeCustomerHandler extends FakeDataHandler
{
    public function list(?string $search, bool $recursive = false): array
    {
        return array_map(
            function () use ($search, $recursive) {
                return $this->load($this->generator->randomNumber(8), $recursive);
            },
            range(0, $this->generator->biasedNumberBetween(2, $recursive ? 5 : 20))
        );
    }

    public function load(int $id, bool $recursive = false)
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
            'addresses' => $gen->optional()->passthrough((new FakeAddressHandler($this->generator))->list($gen->word)) ?: [],
        ];
    }
}
