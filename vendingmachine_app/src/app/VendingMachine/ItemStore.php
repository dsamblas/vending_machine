<?php


namespace App\VendingMachine;


use Domain\Currency\Exception\CurrencyMismatch;
use Domain\Item;
use Domain\Item\Slot;
use Domain\Item\Store;
use Domain\Money;
use Domain\Money\Exception\NotEnoughMoney;
use InvalidArgumentException;

class ItemStore implements Store
{

    private array $slots;

    /**
     * ItemStore constructor.
     * @param Slot[] $slots
     */
    public function __construct(array $slots = [])
    {
        $this->slots = $slots;

    }


    public function buyItem(string $slotCode, Money $money): Item
    {

        if (false == isset($this->slots[$slotCode])) {
            throw new InvalidArgumentException();
        }

        if ($this->slots[$slotCode]->unitPrice()->currency()->code() !== $money->currency()->code()) {
            throw new CurrencyMismatch($this->slots[$slotCode]->unitPrice()->currency()->code(), $money->currency()->code());
        }

        if ($this->slots[$slotCode]->unitPrice()->amount() > $money->amount()) {
            throw new NotEnoughMoney($this->slots[$slotCode]->unitPrice()->amount(), $money->amount());
        }

        return $this->slots[$slotCode]->pop();
    }

    public function setItems(Item $item, string $slotCode, int $qty): void
    {
        $this->slots[$slotCode] = new Slot($item, $slotCode, $qty);
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this->slots as $slot) {
            $result[$slot->code()] = $slot->toArray();
        }
        return $result;
    }
}