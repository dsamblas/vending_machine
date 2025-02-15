<?php


namespace App\VendingMachine;


use Domain\Coin as CoinInterface;
use Domain\Coin\CoinCollection as CoinCollectionInterface;
use Domain\Coin\Slot;
use Domain\Currency;
use Domain\Currency\Exception\CurrencyMismatch;
use Domain\Money as MoneyInterface;
use Domain\Money\Exception\NotEnoughMoney;
use Domain\VendingMachine\Wallet as WalletInterface;
use Domain\VendingMachine\Wallet\Exception\NoExactCoinCombination;


class Wallet implements WalletInterface
{

    private CoinCollectionInterface $wallet;

    public function __construct(CoinCollectionInterface $coins)
    {
        $this->wallet = $coins;
    }

    public function pop(): ?CoinInterface
    {
        return $this->wallet->pop();
    }

    public function toArray(): array
    {
        return $this->wallet->toArray();
    }

    public function remove(CoinInterface $coin, int $qty): void
    {
        $this->wallet->remove($coin, $qty);
    }

    public function push(CoinInterface $coin): void
    {
        $this->wallet->push($coin);
    }

    public function popMoney(MoneyInterface $money): CoinCollectionInterface
    {
        if ($money->currency()->code() !== $this->wallet->currency()->code()) {
            throw new CurrencyMismatch($money->currency()->code(), $this->wallet->currency()->code());
        }

        if ($this->total()->amount() < $money->amount()) {
            throw new NotEnoughMoney($this->total()->amount(), $money->amount());
        }

        $coins = $this->getAmount($money->amount());

        if (empty($coins)) {
            throw new NoExactCoinCombination();
        }

        $coinsArray = $coins->toArray();

        foreach ($coinsArray['coins'] as $coinLabel => $coinSlot) {
            $this->wallet->remove(new Coin($coinLabel, $coinSlot['value'], $this->currency()), $coinSlot['qty']);

        }

        return $coins;
    }

    public function total(): MoneyInterface
    {
        return $this->wallet->total();
    }

    private function getAmount(float $amount): ?CoinCollectionInterface
    {
        $coins = $this->wallet->toArray()['coins'];

        $coinsSortedByValue = $this->array_sort($coins, 'value', SORT_DESC);

        $amountLeft = $amount;
        $result = [];
        foreach ($coinsSortedByValue as $coinLabel => $coinSlot) {
            if (round($amountLeft, 2) >= round($coinSlot['value'], 2)) {
                while ($coinSlot['qty'] > 0 && round($coinSlot['value'], 2) <= round($amountLeft, 2)) {
                    $amountLeft -= $coinSlot['value'];
                    $coinSlot['qty']--;
                    $result[$coinLabel]['value'] = $coinSlot['value'];
                    if (empty($result[$coinLabel]['qty'])) {
                        $result[$coinLabel]['qty'] = 0;
                    }
                    $result[$coinLabel]['qty']++;
                }
            }
        }

        $resultSlots = [];
        foreach ($result as $coinLabel => $slot) {
            $resultSlots[] = new Slot(new Coin($coinLabel, $slot['value'], $this->currency()), $coinLabel, $slot['qty']);
        }
        $collectionResult = new CoinCollection($resultSlots, $this->currency());

        if (empty($result) || 0 < $amount - $collectionResult->total()->amount()) {
            return null;
        }

        return $collectionResult;


    }

    private function array_sort($array, $on, $order = SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    public function currency(): Currency
    {
        return $this->wallet->currency();
    }
}