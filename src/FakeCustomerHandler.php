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
            'aktiv' => $gen->boolean,
            'verantwortlicherID' => $gen->randomNumber(8),
            'kundeID' => $gen->optional()->randomNumber(8),
            'parentAccount' => $recursive ? null : $gen->optional()->passthrough($this->load($gen->randomNumber(8), true)),
            'childAccounts' => $gen->optional(.2)->passthrough($recursive ? null : $this->list($gen->word, true)) ?: [],
            'consumers' => $gen->optional()->passthrough((new FakeConsumerHandler($this->generator))->list($gen->word)) ?: [],
            'addresses' => array_map(
                function () use ($gen) {
                    return [
                        'id' => $gen->randomNumber(8),
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
