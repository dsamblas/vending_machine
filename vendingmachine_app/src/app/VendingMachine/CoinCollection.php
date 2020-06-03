<?php


namespace App\VendingMachine;


use Domain\Coin as CoinInterface;
use Domain\Coin\CoinCollection as CoinCollectionInterface;
use Domain\Coin\Slot;
use Domain\Currency;
use Domain\Currency\Exception\CurrencyMismatch;
use Domain\Money;
use Domain\Money\Exception\NotEnoughMoney;
use InvalidArgumentException;

class CoinCollection implements CoinCollectionInterface
{
    private const DEFAULT_CURRENCY = 'EUR';

    private array $collection;

    private Currency $currency;


    public function __construct(array $coins, Currency $currency)
    {
        if (false === empty($coins)) {
            if ($coins[0] instanceof CoinInterface) {
                foreach ($coins as $coin) {
                    $this->push($coin);
                }
            } elseif ($coins[0] instanceof Slot) {
                foreach ($coins as $coinSlot) {
                    $this->collection[$coinSlot->code()]['qty'] = $coinSlot->qty();
                    $this->collection[$coinSlot->code()]['value'] = $coinSlot->unitPrice()->amount();
                }
            } else {
                throw new InvalidArgumentException();
            }
        } else {
            $this->collection = [];
            $this->currency = $currency;
        }
        $this->currency = $currency;
    }

    public function push(CoinInterface $coin): void
    {
        if (empty($this->currency)) {
            $this->currency = $coin->currency();
            $this->collection = [];
        }
        if ($coin->currency()->code() !== $this->currency()->code()) {
            throw new CurrencyMismatch($coin->currency()->code(), $this->currency()->code());
        }

        if (!isset($this->collection[$coin->label()]['qty'])) {
            $this->collection[$coin->label()]['qty'] = 0;
        }
        $this->collection[$coin->label()]['qty']++;
        $this->collection[$coin->label()]['value'] = $coin->amount();

    }

    public function currency(): Currency
    {
        if (empty($this->currency)) {
            return new Currency(self::DEFAULT_CURRENCY);
        }
        return $this->currency;

    }

    public function pop(): ?CoinInterface
    {
        $coinLabel = $this->getSmallerNonEmptySlotLabel();
        if (empty($coinLabel)) {
            return null;
        }
        $this->collection[$coinLabel]['qty']--;
        $value = $this->collection[$coinLabel]['value'];
        if (0 >= $this->collection[$coinLabel]['qty']) {
            unset($this->collection[$coinLabel]);
        }
        return new Coin($coinLabel, $value, $this->currency());
    }

    private function getSmallerNonEmptySlotLabel(): ?string
    {
        $smaler = null;
        foreach ($this->collection as $coinLabel => $coinSlot) {
            if (0 < $coinSlot['qty']) {
                if (is_null($smaler) || ($smaler['value'] > $coinSlot['value'])) {
                    $smaler = $coinSlot;
                    $smaler['label'] = $coinLabel;
                }
            }
        }
        return is_null($smaler) ? null : $smaler['label'];
    }

    public function remove(CoinInterface $coin, int $qty): void
    {
        if (false === isset($this->collection[$coin->label()])) {
            throw new NotEnoughMoney($qty, 0);
        }
        if ($this->collection[$coin->label()]['qty'] < $qty) {
            throw new NotEnoughMoney($qty, $this->collection[$coin->label()]['qty']);
        }

        $this->collection[$coin->label()]['qty'] -= $qty;
        if (0 >= $this->collection[$coin->label()]['qty']) {
            unset($this->collection[$coin->label()]);
        }
    }

    public function total(): Money
    {
        $result = 0.0;
        foreach ($this->collection as $coinLabel => $coinSlot) {
            $result += $coinSlot['value'] * $coinSlot['qty'];
        }
        return new Money($result, $this->currency());
    }

    public function toArray(): array
    {
        return [
            'coins' => empty($this->collection) ? [] : $this->collection,
            'currency' => $this->currency()->code()
        ];
    }
}