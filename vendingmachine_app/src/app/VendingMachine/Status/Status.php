<?php


namespace App\VendingMachine\Status;


use Domain\Currency;
use Domain\Item\Store;
use Domain\VendingMachine\Status as StatusInterface;
use Domain\VendingMachine\Wallet;

class Status implements StatusInterface
{

    private Wallet $inputWallet;
    private Wallet $exchangeWallet;
    private Store $itemStore;
    private Currency $currency;
    private string $machineId;

    public function __construct(
        string $machineId,
        Wallet $inputWallet,
        Wallet $exchangeWallet,
        Store $itemStore,
        Currency $currency
    )
    {
        $this->machineId = $machineId;
        $this->inputWallet = $inputWallet;
        $this->exchangeWallet = $exchangeWallet;
        $this->itemStore = $itemStore;
        $this->currency = $currency;

    }

    public function toArray(): array
    {
        return [
            "machineId" => $this->machineId(),
            "input" => $this->inputWallet()->toArray(),
            "exchange" => $this->exchangeWallet()->toArray(),
            "stock" => $this->itemStore()->toArray(),
            "currency" => $this->currency()->code()
        ];
    }

    public function machineId(): string
    {
        return $this->machineId;
    }

    public function inputWallet(): Wallet
    {
        return $this->inputWallet;
    }

    public function exchangeWallet(): Wallet
    {
        return $this->exchangeWallet;
    }

    public function itemStore(): Store
    {
        return $this->itemStore;
    }

    public function currency(): Currency
    {
        return $this->currency;
    }
}