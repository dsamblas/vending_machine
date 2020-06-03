<?php


namespace App\Command;

use App\Infrastructure\Output\SystemOutputPrintService;
use App\VendingMachine\Message\MessageFactory;
use Domain\Currency;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;


class ShellInputCommand extends Command
{

    protected static $defaultName = 'vending_machine:shell_input_command';

    private MessageBusInterface $messageBus;
    private Currency $currency;
    private SystemOutputPrintService $printService;
    private MessageFactory $messageFactory;

    public function __construct(MessageBusInterface $messageBus, SystemOutputPrintService $printService, MessageFactory $messageFactory)
    {
        parent::__construct(self::$defaultName);
        $this->messageBus = $messageBus;
        $this->printService = $printService;
        $this->messageFactory = $messageFactory;

    }


    protected function configure()
    {
        $this
            ->addArgument(
                'arguments',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'colon space separated commands list'
            )
            ->addOption('currency', null, InputOption::VALUE_REQUIRED, 'Currency code', 'EUR');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->currency = new Currency($input->getOption('currency'));
        $arguments = $input->getArgument('arguments');
        $cleanArguments = [];
        foreach ($arguments as $arg) {
            $cleanArguments[] = $this->cleanArgument($arg);
        }

        $messages = $this->messageFactory->buildFromShellInputCommandArgs($this->currency, $cleanArguments);

        foreach ($messages as $message) {
            $this->messageBus->dispatch($message);
        }

        $this->printService->print($output);
        return 0;
    }

    private function cleanArgument(string $arg): string
    {
        return trim($arg, ' ,');

    }

}