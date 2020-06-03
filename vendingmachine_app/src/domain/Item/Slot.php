<?php


namespace Domain\Item;

use Domain\Item;
use Domain\Item\Slot\Exception\NotEnoughItemInSlot;
use Domain\Money;

class Slot
{
    private Item $item;
    private int $qty;
    private string $code;

    public function __construct(Item $item, string $code, int $qty)
    {
        $this->item = $item;
        $this->qty = $qty;
        $this->code = $code;
    }

    public function pop(): Item
    {
        if ($this->qty <= 0) {
            throw new NotEnoughItemInSlot($this->item->name(), $this->code);
        }
        $this->qty--;
        return clone $this->item;
    }

    public function unitPrice(): Money
    {
        return $this->item->unitPrice();
    }

    public function code()
    {
        return $this->code;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->item->name(),
            'unitPrice' => $this->item->unitPrice()->amount(),
            'qty' => $this->qty
        ];
    }


}