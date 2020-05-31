<?php


namespace Domain\VendingMachine;


use Domain\Currency;
use Domain\Item\Store;

interface Status
{
    public function inputWallet(): Wallet;

    public function exchangeWallet(): Wallet;

    public function itemStore(): Store;

    public function currency(): Currency;

    public function toArray(): array;

}