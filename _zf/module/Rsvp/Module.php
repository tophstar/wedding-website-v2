<?php

 namespace Rsvp;

 use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
 use Zend\ModuleManager\Feature\ConfigProviderInterface;
 use Rsvp\Model\Rsvp;
 use Rsvp\Model\RsvpTable;
 use Rsvp\Model\Auth;
 use Rsvp\Model\AuthTable;
 use Zend\Db\ResultSet\ResultSet;
 use Zend\Db\TableGateway\TableGateway;

 class Module implements AutoloaderProviderInterface, ConfigProviderInterface
 {
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

     public function getConfig()
     {
         return include __DIR__ . '/config/module.config.php';
     }

     public function getServiceConfig()
     {
         return array(
             'factories' => array(
                 'Rsvp\Model\RsvpTable' =>  function($sm) {
                     $tableGateway = $sm->get('RsvpTableGateway');
                     $table = new RsvpTable($tableGateway);
                     return $table;
                 },

                 'RsvpTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Rsvp());
                     return new TableGateway('rsvp', $dbAdapter, null, $resultSetPrototype);
                 },

                 'Rsvp\Model\AuthTable' =>  function($sm) {
                     $tableGateway = $sm->get('AuthTableGateway');
                     $table = new AuthTable($tableGateway);
                     return $table;
                 },

                 'AuthTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                     $resultSetPrototype = new ResultSet();
                     $resultSetPrototype->setArrayObjectPrototype(new Auth());
                     return new TableGateway('auth', $dbAdapter, null, $resultSetPrototype);
                 },
             ),
         );
     }

 }