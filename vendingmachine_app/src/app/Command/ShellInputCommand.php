<?php


namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ShellInputCommand extends Command
{

    protected static $defaultName = 'vending_machine:shell_input_command';

    protected function configure()
    {
        $this
            // ...
            ->addArgument(
                'arguments',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'colon space separated commands list'
            );

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arguments = $input->getArgument('arguments');
        foreach ($arguments as $arg) {
            $output->writeln($arg);
        }
        return 0;
    }
}