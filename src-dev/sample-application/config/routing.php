<?php
return [
    'routes' => [
        'simple-route' => [
            'http-verb'             => 'GET',
            'path'                  => '/person/{id}',
            'controller-service-id' => 'ents.http-mvc-service.dev.sample-controller',
            'action-method-name'    => 'simpleAction',
        ],
        'route-with-generic-error' => [
            'http-verb'             => 'GET',
            'path'                  => '/error/generic-exception',
            'controller-service-id' => 'ents.http-mvc-service.dev.controller-with-errors',
            'action-method-name'    => 'throwGenericException',
        ],
        'route-with-error-and-status-code' => [
            'http-verb'             => 'GET',
            'path'                  => '/error/exception-with-status-code',
            'controller-service-id' => 'ents.http-mvc-service.dev.controller-with-errors',
            'action-method-name'    => 'throwNotAuthorisedException',
        ],
    ]
];
