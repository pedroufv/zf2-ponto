<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'album' => 'Album\Controller\AlbumController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'album' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/album[/][:controller][/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'album',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'album' => __DIR__ . '/../view',
        ),
    ),
    
    'doctrine' => array(
        'driver' => array(
            'Album_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Album/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                     'Album\Entity' =>  'Album_driver'
                ),
            ),
        ),
    ),                
);