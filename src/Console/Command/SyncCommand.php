<?php

namespace Hity\Console\Command;

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
        $classes = self::get_php_classes($code);

        foreach ($classes as $class) {
            $this->syncModel($code, $class);
        }
    }

    protected function syncModel(&$fileCode, $className) {
        echo "Sync class $className \n";
        $reflection = new \ReflectionClass($this->modelNamespace . "\\" . $className);
        $methods = $reflection->getMethods();
        foreach ($methods as $method) {
            // echo "method $method \n";
        }

        $fieldsMethod = $reflection->getMethod('fields');
        print_r($fieldsMethod);
    }

    static function get_php_classes($php_code) {
        $classes = array();
        $tokens = token_get_all($php_code);
        $count = count($tokens);
        for ($i = 2; $i < $count; $i++) {
            if (   $tokens[$i - 2][0] == T_CLASS
                && $tokens[$i - 1][0] == T_WHITESPACE
                && $tokens[$i][0] == T_STRING) {

                $class_name = $tokens[$i][1];
                $classes[] = $class_name;
            }
        }
        return $classes;
    }

}