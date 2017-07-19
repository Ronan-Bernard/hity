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
            $sql = "CREATE TABLE $this->tableName(";
            $reflectionMethod = $reflection->getMethod('fields');

            // add ID - to customize ?
            $sql .= "id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,";

            foreach ($reflectionMethod->getParameters() as $param) {

                if ($param->hasType()) {
                    $typesArray = array(
                        'string' => 'TEXT',
                        'int' => 'INT(11)',
                        'float' => 'FLOAT(7,4)',
                        'bool' => 'TINYINT(1)'
                    );
                    $paramType = strtolower($param->getType());
                    $fieldType = $typesArray[$paramType];
                } else {
                    $fieldType = 'VARCHAR(255)';
                }
                $sql .= sprintf(' %s %s,', $param->getName(), $fieldType);

                // TODO lire un enum sous forme de class anonyme ?
                // TODO a default value could be given
            }
            $sql = substr($sql, 0, -1); // remove trailing comma

            $sql .= ")";
                echo "\n" . $sql . "\n"; die;
            $this->pdo->prepare($sql);
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