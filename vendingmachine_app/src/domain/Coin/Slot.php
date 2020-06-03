<?php


namespace Domain\Coin;

use Domain\Coin;
use Domain\Coin\Slot\Exception\NotEnoughCoinInSlot;
use Domain\Money;

class Slot
{
    private Coin $coin;
    private int $qty;
    private string $code;

    public function __construct(Coin $coin, string $code, int $qty)
    {
        $this->coin = $coin;
        $this->qty = $qty;
        $this->code = $code;
    }

    public function pop(): Coin
    {
        if ($this->qty <= 0) {
            throw new NotEnoughCoinInSlot($this->coin, $this->code);
        }
        $this->qty--;
        return clone $this->coin;
    }

    public function total(): Money
    {
        return new Money($this->coin->amount() * $this->qty, $this->coin->currency());
    }

    public function unitPrice(): Money
    {
        return new Money($this->coin->amount(), $this->coin->currency());
    }

    public function code(): string
    {
        return $this->code;
    }

    public function qty(): int
    {
        return $this->qty;
    }

    public function toArray(): array
    {
        return [
            $this->code => [
                'label' => $this->coin->label(),
                'value' => $this->coin->amount(),
                'qty' => $this->qty
            ]
        ];
    }


}