<?php

namespace Hity\Console\Command;

use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Output\OutputInterface
};
use StudioAPI\Model\Personnage;

class SyncCommand extends Command
{
    protected function configure()
    {
        $this->setName('sync')
            ->setDescription('Synchronise les modeles avec la structure de la base de donnees.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Commande lancee.');

        $this->detectModelFiles();
    }

    protected function detectModelFiles() {
        // les classes ne sont pas encore loadées
        // du coup il vaut mieux avoir une conf où lire l'emplacement,
        // ou lire le domaine dont on va lire les \Model\*

    }



}