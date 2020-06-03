<?php


namespace App\Infrastructure\Output;


use Domain\Output\OutputPrinter;
use Symfony\Component\Console\Output\OutputInterface;

class SystemOutputPrintService implements OutputPrinter
{
    private array $outputMsgs;

    public function __construct()
    {
        $this->outputMsgs = [];
    }


    public function add(string $msg): void
    {
        $this->outputMsgs[] = $msg;
    }

    public function print(OutputInterface $output): void
    {
        $output->write(implode(', ', $this->outputMsgs));
        $this->outputMsgs = [];
    }

}