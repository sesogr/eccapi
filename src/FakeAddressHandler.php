<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

class FakeAddressHandler extends FakeDataHandler
{
    public function list(?string $search, bool $recursive = false): array
    {
        return array_map(
            function () use ($search) {
                $item = $this->load($this->generator->randomNumber(8));
                $item['line1'] = $search
                    ? implode(
                        ' ',
                        $this->generator->shuffleArray([$search] + explode(' ', $item['line1']))
                    )
                    : $item['line1'];
                return $item;
            },
            range(0, $this->generator->biasedNumberBetween(2, 20))
        );
    }

    public function load(int $id, bool $recursive = false)
    {
        return [
            'id' => $id,
            'line1' => $this->generator->streetAddress,
            'line2' => $this->generator->optional(.1)->secondaryAddress,
            'line3' => null,
            'postalCode' => $this->generator->optional(.8)->postcode,
            'city' => $this->generator->optional(.8)->city,
            'country' => $this->generator->optional()->country,
        ];
    }
}
