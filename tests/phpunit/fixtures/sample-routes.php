<?php
return [
    'routes' => [
        'route1' => [
            'http-verb'             => 'GET',
            'path'                  => '/person/{id}',
            'controller-service-id' => 'sainsburys.sainsburys-http-service.dev.some-controller',
            'action-method-name'    => 'simpleAction',
        ],
    ]
];
