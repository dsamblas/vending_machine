<?php


namespace Domain\Output;

use Symfony\Component\Console\Output\OutputInterface;

interface OutputPrinter
{
    public function print(OutputInterface $output): void;

    public function add(string $msg): void;
}