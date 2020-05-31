<?php


namespace Domain\Item;

use Domain\Item\Slot\Exception\NotEnoughItemInSlot;

class Slot
{
    private $item;
    private $qty;
    private $code;

    public function __construct(Item $item, string $code, int $qty)
    {
        $this->item = $item;
        $this->qty = $qty;
        $this->code = $code;
    }

    public function pop(): Item
    {
        if ($this->qty <= 0) {
            throw new NotEnoughItemInSlot($this->item, $this->code);
        }
        $this->qty--;
        return clone $this->item;
    }


}