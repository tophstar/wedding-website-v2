<?php

 return array(
     'controllers' => array(
         'invokables' => array(
             'Rsvp\Controller\Rsvp' => 'Rsvp\Controller\RsvpController',
         ),
     ),

     // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             'rsvp' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/rsvp[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[0-9]+',
                     ),
                     'defaults' => array(
                         'controller' => 'Rsvp\Controller\Rsvp',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),

     'view_manager' => array(
         'template_path_stack' => array(
             'rsvp' => __DIR__ . '/../view',
         ),
         'strategies' => array(
         	'ViewJsonStrategy'
         ),
     ),
 );