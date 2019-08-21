<?php

namespace Ponto;

use Ponto\Model\PontosTable;
use Ponto\Model\UsuariosTable;
use Zend\Mvc\MvcEvent;

class Module
{
    
    # carrega as traduções
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Ponto\Model\PontosTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new PontosTable($dbAdapter);
                    return $table;
                },
                'Ponto\Model\UsuariosTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter'); 
                    $table = new UsuariosTable($dbAdapter);
                    return $table;
                },
            ),
        );
    }    

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}