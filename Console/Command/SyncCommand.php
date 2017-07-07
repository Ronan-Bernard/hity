<?php

namespace Hity\Console\Command;

use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Output\OutputInterface
};

class SyncCommand extends Command
{
    protected function configure()
    {
        $this->setName('sync')
            ->setDescription('Synchronise les modèles avec la structure de la base de données.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Customer registered.');
    }
}