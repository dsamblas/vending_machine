<?php


namespace Domain;


interface Coin extends Money
{
    public function label(): string;
}