<?php


namespace App\VendingMachine;


use Domain\Item as ItemInterface;
use Domain\Money;

class Item implements ItemInterface
{

    private string $name;
    private Money $unitPrice;

    public function __construct(string $name, Money $unitPrice)
    {
        $this->name = $name;
        $this->unitPrice = $unitPrice;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function unitPrice(): Money
    {
        return $this->unitPrice;
    }
}