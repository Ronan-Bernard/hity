<?php

namespace Hity\Core;

use Hity\Util\DB;

class CoreModelSync
{
    private $classToSync;
    private $classNamespace;
    private $tableName;
    private $pdo;

    public function __construct(String $className, String $namespace)
    {
        $this->pdo = DB::getInstance();
        echo gettype($this->pdo);
        $this->setClassToSync($className);
        $this->setClassNamespace($namespace);
        $this->syncModel();
    }

    protected function syncModel() {
        echo "Synchronizing class $this->classToSync \n";
        $reflection = new \ReflectionClass(
            $this->classNamespace . "\\" . $this->classToSync
        );

        $this->decideTableName($reflection);

        $columns = $this->retrieveColumnsForCurrentTableName();

        if (empty($columns)) {
            $this->createTableForCurrentTableName($reflection);
        } else {
            // compare table and fields : addition, deletion, then comparison field by field
            // $columns vs $fieldsMethod
            $fieldsMethod = $reflection->getMethod('fields');

            foreach ($reflection->getMethods() as $method) {
                // echo "method $method \n";
            }
        }
    }

    /**
     * @param \ReflectionClass $reflection
     * @todo check valid characters in table name
     */
    protected function decideTableName(\ReflectionClass $reflection)
    {
        if ($reflection->hasProperty('name')) {
            $this->setTableName($reflection->getProperty('name'));
        } else {
            $this->setTableName($this->getClassToSync());
        }
    }

    /**
     * @return mixed
     * @todo db error handling / abstract for db other than mysql
     */
    protected function retrieveColumnsForCurrentTableName()
    {
        $tableColumns = 'SHOW COLUMNS FROM :tableName';
        $tableColumnsStmt = $this->pdo->prepare($tableColumns);
        $tableColumnsStmt->execute(array(
            ':tableName' => $this->getTableName()
        ));
        return $tableColumnsStmt->fetchAll();
    }

    protected function createTableForCurrentTableName(\ReflectionClass $reflection)
    {
        try {
            $createTable = "CREATE TABLE $this->tableName(";
            $reflectionMethod = $reflection->getMethod('fields');

            // add ID - to customize ?
            $createTable .= "id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";

            foreach ($reflectionMethod->getParameters() as $param) {
                // de type ReflectionParameter avec un name = nom de l'arg
                // j'espère qu'on peut choper le typehint sinon c mort
                // -->hasType et getType ?
            }

            $createTable .= ")";
                echo "<br/>" . $createTable; die;
            $this->pdo->prepare($createTable);
            $this->pdo->execute();
        } catch (\PDOException $e) {
            print "Erreur à la création de la table !: "
                . $e->getMessage() . "<br/>";
            die();
        }
    }


    // ***************** GETTERS ** AND ** SETTERS **********************

    /**
     * @return String
     */
    public function getClassToSync()
    {
        return $this->classToSync;
    }

    /**
     * @param mixed $classToSync
     */
    public function setClassToSync($classToSync)
    {
        $this->classToSync = $classToSync;
    }

    /**
     * @return mixed
     */
    public function getClassNamespace()
    {
        return $this->classNamespace;
    }

    /**
     * @param String $classNamespace
     */
    public function setClassNamespace($classNamespace)
    {
        $this->classNamespace = $classNamespace;
    }

    /**
     * @return String
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param String $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

}