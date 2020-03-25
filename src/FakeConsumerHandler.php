<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

class FakeConsumerHandler extends FakeDataHandler
{
    public function list(?string $search, bool $recursive = false): array
    {
        return array_map(
            function () use ($search) {
                $item = $this->load($this->generator->randomNumber(8));
                $item['bemerkungen'] = $search
                    ? implode(
                        ' ',
                        $this->generator->shuffleArray([$search] + explode(' ', $item['bemerkungen']))
                    )
                    : $item['bemerkungen'];
                return $item;
            },
            range(0, $this->generator->biasedNumberBetween(2, 20))
        );
    }

    public function load(int $id, bool $recursive = false)
    {
        $gen = $this->generator;
        return [
            'id' => $id,
            'zaehlernummer' => $gen->ean13,
            'melo' => $gen->ean13,
            'malo' => $gen->optional(8)->ean13,
            'editable' => $gen->boolean,
            'aktiv' => $gen->boolean,
            'verbrauchertyp' => $gen->boolean(80) ? 'Strom' : 'Gas',
            'slpBerechnung' => $gen->boolean,
            'bemerkungen' => $this->generator->words($this->generator->numberBetween(3, 10), true),
            'kostenstelle' => $this->generator->optional()->word,
            'isEditable' => $gen->boolean,
            'lieferspannungID' => $gen->randomNumber(8),
            'messspannungID' => $gen->randomNumber(8),
            'konzessionsabgabeHTID' => $gen->randomNumber(8),
            'konzessionsabgabeNTID' => $gen->randomNumber(8),
            'slpTypId' => $this->generator->randomElement(['SLP_ET', 'SLP_ZT', 'SLP_MT', 'SLP_MAX', 'SLP_WP', 'SLP_SH']),
            'evuID' => $gen->randomNumber(8),
            'lieferantID' => $gen->randomNumber(8),
            'netzbetreiberID' => $gen->randomNumber(8),
            'kundeId' => $gen->randomNumber(8),
            'address' => array_map(
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
