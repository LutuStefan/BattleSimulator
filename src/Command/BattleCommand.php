<?php

namespace App\Command;

use App\Character;
use App\Service\TurnService;
use App\Turn;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BattleCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:battle';

    protected static $defaultDescription = "Run battle between Orderus and a beast!";

    protected function configure(): void
    {
        // ...
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            /** @var Character $hero
             * @var  Character $beast
             */
            [$hero, $beast] = TurnService::initBattle();
            $hero->displayStatus();
            $beast->displayStatus();

            $turn = new Turn($hero, $beast);
            $turn->executeTurns();
        } catch (\Exception $exception) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
