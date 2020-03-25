<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

class FakeEvuHandler extends FakeDataHandler
{
    public function list(?string $search): array
    {
        return array_map(
            function () use ($search) {
                $item = $this->load($this->generator->randomNumber(8));
                $item['kommentar'] = $search
                    ? implode(
                        ' ',
                        $this->generator->shuffleArray([$search] + explode(' ', $item['kommentar']))
                    )
                    : $item['kommentar'];
                return $item;
            },
            range(0, $this->generator->biasedNumberBetween(2, 20))
        );
    }

    public function load(int $id)
    {
        return [
            'id' => $id,
            'evuName' => $this->generator->company . ($this->generator->boolean ? ', ' . $this->generator->city : ''),
            'evuNummer' => $this->generator->ean13,
            'aktiv' => $this->generator->boolean,
            'kommentar' => $this->generator->words($this->generator->numberBetween(3, 10), true),
            'standardKuendigungsfrist' => $this->generator->randomElement(['none', 'unknown', 'd1', 'w1', 'w2', 'w4', 'w6', 'm1', 'm3', 'm6']),
        ];
    }
}
