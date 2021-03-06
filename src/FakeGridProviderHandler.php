<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

class FakeGridProviderHandler extends FakeDataHandler
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
        return [
            'id' => $id,
            'netzbetreiberName' => $this->generator->company . ($this->generator->boolean ? ', ' . $this->generator->city : ''),
            'netzbetreiberCode' => $this->generator->ean13,
            'kommentar' => $this->generator->words($this->generator->numberBetween(3, 10), true),
            'lastChanged' => $this->generator->date(DATE_ATOM),
        ];
    }
}
