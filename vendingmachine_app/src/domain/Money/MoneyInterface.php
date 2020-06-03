<?php


namespace Domain\Money;


use Domain\Currency;

interface MoneyInterface
{
    public function amount(): float;

    public function currency(): Currency;
}