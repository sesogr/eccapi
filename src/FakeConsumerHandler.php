<?php declare(strict_types=1);
namespace SuiteCrmCalcApi;

class FakeConsumerHandler extends FakeDataHandler
{
    public function list(?string $search, bool $isExtended = false): array
    {
        $customers = $isExtended ? (new FakeCustomerHandler($this->generator))->list($search) : [];
        return array_map(
            function () use ($search, $isExtended, $customers) {
                $item = $this->load($this->generator->uuid);
                if ($isExtended) {
                    $item['customer'] = $this->generator->randomElement($customers);
                    $item['kundeID'] = $item['customer']['id'];
                }
                $item['bemerkungen'] = $search
                    ? implode(
                        ' ',
                        $this->generator->shuffleArray([$search] + explode(' ', $item['bemerkungen']))
                    )
                    : $item['bemerkungen'];
                return $item;
            },
            range(0, $this->generator->biasedNumberBetween(5, 100))
        );
    }

    public function load(string $id, bool $isExtended = false)
    {
        $gen = $this->generator;
        $customerId = $gen->uuid;
        return [
            'id' => $id,
            'zaehlernummer' => $gen->ean13,
            'melo' => $gen->ean13,
            'malo' => $gen->optional(8)->ean13,
            'verbrauchertyp' => $gen->boolean(80) ? 'Strom' : 'Gas',
            'slpBerechnung' => $gen->boolean,
            'bemerkungen' => $this->generator->words($this->generator->numberBetween(3, 10), true),
            'kostenstelle' => $this->generator->optional()->word,
            'kundeID' => $customerId,
            'addresses' => $gen->optional()->passthrough((new FakeAddressHandler($this->generator))->list(null)) ?: [],
        ] + ($isExtended ? ['customer' => (new FakeCustomerHandler($this->generator))->load($customerId)] : []);
    }
}
