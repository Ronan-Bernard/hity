<?php

namespace Hity\Console\Command;

use Hity\Util\Parser;
use Symfony\Component\Console\{
    Command\Command,
    Input\InputInterface,
    Output\OutputInterface
};
use StudioAPI\Model;

class SyncCommand extends Command
{
    private $modelFolder = 'src/StudioAPI/Model';
    private $modelNamespace = '\\StudioAPI\\Model';

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
        // TODO gérer autrement le répertoire des model ?
        foreach (new \DirectoryIterator($this->modelFolder) as $fileInfo) {
            if($fileInfo->isDot()) continue;
            $this->syncModelFile($fileInfo->getFilename());
        }
    }

    protected function syncModelFile(String $filename) {
        $code = file_get_contents($this->modelFolder . '/' . $filename);
        $classes = Parser::get_php_classes($code);

        foreach ($classes as $class) {
            $this->syncModel($code, $class);
        }
    }

    protected function syncModel(&$fileCode, $className) {
        echo "Sync class $className \n";
        $reflection = new \ReflectionClass($this->modelNamespace . "\\" . $className);
        foreach ($reflection->getMethods() as $method) {
            // echo "method $method \n";
        }

        $fieldsMethod = $reflection->getMethod('fields');
        print_r($fieldsMethod);
    }
}