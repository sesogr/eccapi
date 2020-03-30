<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

class FakeAddressHandler extends FakeDataHandler
{
    public function list(?string $search): array
    {
        return array_map(
            function () use ($search) {
                $item = $this->load($this->generator->uuid);
                $item['line1'] = $search
                    ? implode(
                        ' ',
                        $this->generator->shuffleArray([$search] + explode(' ', $item['line1']))
                    )
                    : $item['line1'];
                return $item;
            },
            range(0, $this->generator->biasedNumberBetween(0, 3))
        );
    }

    public function load(string $id)
    {
        return [
            'id' => $id,
            'line1' => $this->generator->streetAddress,
            'line2' => $this->generator->optional(.1)->jobTitle,
            'line3' => null,
            'postalCode' => $this->generator->optional(.8)->postcode,
            'city' => $this->generator->optional(.8)->city,
            'country' => $this->generator->optional()->country,
        ];
    }
}
