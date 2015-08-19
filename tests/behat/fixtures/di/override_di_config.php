<?php

use Pimple\Container;

return array(

    'services' => array(

        'ents.XXXXXX' => function (Container $container) {
            return new Xxxxxx;
        },

    ),
);
