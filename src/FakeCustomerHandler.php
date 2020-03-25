<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

class FakeCustomerHandler extends FakeDataHandler
{
    public function list(?string $search, bool $recursive = false): array
    {
        return array_map(
            function () use ($search, $recursive) {
                return $this->load($this->generator->ean8, $recursive);
            },
            range(0, $this->generator->biasedNumberBetween(2, $recursive ? 5 : 20))
        );
    }

    public function load(string $id, bool $recursive = false)
    {
        $gen = $this->generator;
        return [
            'id' => $id,
            'kundeName' => $gen->company . ($gen->boolean ? ', ' . $gen->city : ''),
            'kundeNummer' => $gen->ean13,
            'aktiv' => $gen->boolean,
            'verantwortlicherID' => $gen->ean8,
            'kundeID' => $gen->optional()->ean8,
            'parentAccount' => $recursive ? null : $gen->optional()->passthrough($this->load($gen->ean8, true)),
            'childAccounts' => $recursive ? [] : $gen->optional()->passthrough($this->list($gen->word, true)),
            'consumers' => [],
            'addresses' => array_map(
                function () use ($gen) {
                    return [
                        'id' => $gen->ean8,
                        'street' => $gen->optional(.8)->streetAddress,
                        'zipCode' => $gen->optional(.8)->postcode,
                        'city' => $gen->optional(.8)->city,
                        'country' => $gen->optional()->country,
                    ];
                },
                range(0, $gen->biasedNumberBetween(0, 3))
            ),
        ];
    }
}
