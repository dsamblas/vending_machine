<?php


namespace Domain;

use Domain\Money\MoneyInterface;


interface Coin extends MoneyInterface
{
    public function label(): string;
}