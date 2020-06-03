<?php


namespace App\Command;

use App\VendingMachine\Command\SetStatusCommand;
use App\VendingMachine\Status\StatusFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;


class ShellInputCommand extends Command
{

    protected static $defaultName = 'vending_machine:shell_input_command';

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        parent::__construct(self::$defaultName);
        $this->messageBus = $messageBus;
    }


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
        $cleanArguments = [];
        foreach ($arguments as $arg) {
            $cleanArguments[] = $this->cleanArgument($arg);
        }

        $messages = $this->messageFactory($cleanArguments);

        foreach ($messages as $message) {
            $this->messageBus->dispatch($message);
        }

        return 0;
    }

    private function cleanArgument(string $arg): string
    {
        return trim($arg, ' ,');

    }

    private function messageFactory(array $args): array
    {
        if ($args[0] === 'SERVICE') {
            return [new SetStatusCommand(StatusFactory::fromArgs($args))];
        }

        return [];

    }

}