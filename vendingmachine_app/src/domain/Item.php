<?php


namespace Domain;

interface Item
{
    public function name(): string;

    public function unitPrice(): Money;
}
