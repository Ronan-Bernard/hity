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
            $this->createTableForCurrentTableName();
        } else {
            // compare table and fields : addition, deletion, then comparison field by field
            // $columns vs $fieldsMethod
            $fieldsMethod = $reflection->getMethod('fields');

            foreach ($reflection->getMethods() as $method) {
                // echo "method $method \n";
            }
        }
    }

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

    protected function createTableForCurrentTableName()
    {
        try {
            $createTable = "CREATE TABLE $this->tableName(";

            $createTable .= ")";
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