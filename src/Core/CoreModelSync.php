<?php

namespace Hity\Core;

use Hity\Util\DB;

class CoreModelSync
{
    private $classToSync;
    private $classNamespace;
    private $pdo;

    public function __construct(String $className, String $namespace)
    {
        $this->pdo = DB::getInstance();
        $this->setClassToSync($className);
        $this->setClassNamespace($namespace);
        $this->syncModel();
    }

    protected function syncModel() {
        echo "Sync class $this->classToSync \n";
        $reflection = new \ReflectionClass(
            $this->classNamespace . "\\" . $this->classToSync
        );
        foreach ($reflection->getMethods() as $method) {
            // echo "method $method \n";
        }

        $fieldsMethod = $reflection->getMethod('fields');
        // check if table exists in db
        $tableColumns = 'SHOW COLUMNS FROM :table';
        $tableColumnsStmt = $this->pdo->prepare($tableColumns);
        $tableColumnsStmt->execute(array(':table' => $this->classToSync));

        // compare table and fields : addition, deletion, then comparison field by field

        print_r($fieldsMethod);
    }

    /**
     * @return mixed
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
     * @param mixed $classNamespace
     */
    public function setClassNamespace($classNamespace)
    {
        $this->classNamespace = $classNamespace;
    }

}