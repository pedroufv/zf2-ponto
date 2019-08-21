<?php
 
return array(
    # definir controllers
    'controllers' => array(
        'invokables' => array(
            'PontoController'    => 'Ponto\Controller\PontoController',
            'UsuarioController'    => 'Ponto\Controller\UsuarioController'
        ),
    ),
 
    # definir rotas
    'router' => array(
        'routes' => array(
        	'pontos' => array(
               'type'      => 'segment',
               'options'   => array(
                   'route'    => '/pontos[/:ano][/:mes]',
                   'constraints' => array(
                       'ano'     => '[0-9]+',
                       'mes'     => '[0-9]+',
                   ),
                   'defaults' => array(
                       'controller' => 'PontoController',
                       'action'     => 'ano',
                   ),
               ),
            ),
            'ponto' => array(
               'type'      => 'segment',
               'options'   => array(
                   'route'    => '/ponto[/:action][/:id]',
                   'constraints' => array(
                       'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                       'id'     => '[0-9]+',
                   ),
                   'defaults' => array(
                       'controller' => 'PontoController',
                       'action'     => 'index',
                   ),
               ),
            ),
            'usuario' => array(
               'type'      => 'segment',
               'options'   => array(
                   'route'    => '/usuario[/:action][/:id]',
                   'constraints' => array(
                       'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                       'id'     => '[0-9]+',
                   ),
                   'defaults' => array(
                       'controller' => 'UsuarioController',
                       'action'     => 'index',
                   ),
               ),
            ),
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'PontoController',
                        'action'     => 'index',
                        ),
                    ),
            ),                        
        ),
    ),
 
    # definir layouts, erros, exceptions, doctype base
    'view_manager' => array(
        'template_path_stack' => array(
            'album' => __DIR__ . '/../view',
        ),
    )
);