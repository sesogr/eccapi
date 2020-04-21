<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

class FakeBiddingHandler extends FakeDataHandler
{
    public function list(?string $search, bool $isExtended = false): array
    {
        return array_map(
            function () use ($search) {
                $item = $this->load($this->generator->uuid);
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

    public function load(string $id, bool $isExtended = false)
    {
        $gen = $this->generator;
        return [
            'id' => $id,
            'userID' => $gen->uuid,
            'biddingName' => $gen->words($gen->numberBetween(3, 10), true),
            'aktiv' => $gen->boolean,
            'deliveryStart' => $gen->date(DATE_ATOM),
            'deliveryEnd' => $gen->date(DATE_ATOM),
            'comment' => $gen->words($gen->numberBetween(3, 10), true),
            'consumerIDList' => array_map(
                function () use ($gen) {
                    return $gen->uuid;
                },
                range(0, $gen->biasedNumberBetween(2, 20))
            ),
        ];
    }
}
