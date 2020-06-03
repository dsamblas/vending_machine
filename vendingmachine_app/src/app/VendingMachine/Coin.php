<?php


namespace App\VendingMachine;

use Domain\Coin as CoinInterface;
use Domain\Currency;

class Coin implements CoinInterface
{

    private string $label;
    private float $amount;
    private Currency $currency;

    public function __construct(string $label, float $amount, Currency $currency)
    {
        $this->label = $label;
        $this->amount = $amount;
        $this->currency = $currency;
    }


    public function label(): string
    {
        return $this->label;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }
}