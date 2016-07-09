<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

Router::plugin('Bakkerij/OAuth2', ['path' => '/oauth'], function (RouteBuilder $routes) {
//    $routes->extensions(['json', 'html']);
    $routes->connect(
        '/authorize',
        [
            'controller' => 'Auth',
            'action' => 'authorize'
        ]
    );
    $routes->connect(
        '/access_token',
        [
            'controller' => 'Auth',
            'action' => 'access_token'
        ]
    );
    $routes->fallbacks('DashedRoute');
});
