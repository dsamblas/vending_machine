<?php


namespace Domain;


interface Money
{
    public function amount(): float;

    public function currency(): Currency;
}