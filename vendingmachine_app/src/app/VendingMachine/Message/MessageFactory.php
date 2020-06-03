<?php


namespace App\VendingMachine\Message;


use App\Infrastructure\File\Repository\JsonFileItemRepository;
use App\VendingMachine\Coin;
use App\VendingMachine\Command\BuyItemCommand;
use App\VendingMachine\Command\InsertCoinCommand;
use App\VendingMachine\Command\ReturnInboxCommand;
use App\VendingMachine\Command\SetStatusCommand;
use App\VendingMachine\Status\StatusFactory;
use Domain\Currency;

class MessageFactory
{
    private StatusFactory $statusFactory;
    private JsonFileItemRepository $itemRepository;

    public function __construct(StatusFactory $statusFactory, JsonFileItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
        $this->statusFactory = $statusFactory;
    }

    public function buildFromShellInputCommandArgs(Currency $currency, array $args): array
    {
        if ($args[0] === 'SERVICE') {
            return [new SetStatusCommand($this->statusFactory->fromArgs($args))];
        }

        $messages = [];
        foreach ($args as $arg) {
            if ('RETURN-COIN' === $arg) {
                $messages[] = new ReturnInboxCommand();
            }

            if (str_starts_with($arg, 'GET-')) {
                $messages[] = $this->buildBuyItemCommandFromArg($arg);
            }
            if (is_numeric($arg)) {
                $messages[] = new InsertCoinCommand(
                    new Coin(
                        implode('_', ['coin', $arg, $currency->code()]),
                        (float)$arg,
                        $currency
                    )
                );
            }
        }

        return $messages;

    }

    private function buildBuyItemCommandFromArg(string $arg): BuyItemCommand
    {
        $slotCode = substr($arg, strlen('GET-'));
        return new BuyItemCommand($this->itemRepository->getBySlotCode($slotCode));

    }

}