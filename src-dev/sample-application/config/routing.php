<?php
return [
    'routes' => [
        'example-route-1' => [
            'http-verb'             => 'GET',
            'path'                  => '/person/{id}',
            'controller-service-id' => 'ents.http-mvc-service.dev.sample-controller',
            'action-method-name'    => 'simpleAction',
        ]
    ]
];
