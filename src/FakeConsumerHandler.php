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
            'verbrauchertyp' => $gen->boolean(80) ? 'Strom' : 'Gas',
            'slpBerechnung' => $gen->boolean,
            'bemerkungen' => $this->generator->words($this->generator->numberBetween(3, 10), true),
            'kostenstelle' => $this->generator->optional()->word,
            'kundeID' => $gen->randomNumber(8),
            'addresses' => $gen->optional()->passthrough((new FakeAddressHandler($this->generator))->list($gen->word)) ?: [],
        ];
    }
}
