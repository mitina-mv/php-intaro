<?php

namespace App\System;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\Setup;
use PDO;
use Exception;

class ORM
{
    private $params;
    private $modelManager;

    public function __construct($params)
    {
        $this->params = $params;
        
        $config = ORMSetup::createAnnotationMetadataConfiguration(
            ['src/Model'],
            true
        );
        $modelManager = EntityManager::create($this->params, $config);

        $this->setModelManager($modelManager);
    } 

    public function setModelManager($modelManager)
    {
        $this->modelManager = $modelManager;
    }

    public function getModelManager()
    {
        return $this->modelManager;
    }
}
