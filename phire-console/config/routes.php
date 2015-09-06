<?php

return [
    'archive [<type>]' => [
        'controller' => 'Phire\Console\Controller\SqlController',
        'action'     => 'dump',
    ],
    '<controller> [<action>] [<param>]' => [
        'prefix' => 'Phire\Console\Controller\\'
    ],
    '*' => [
        'controller' => 'Phire\Console\Controller\ConsoleController',
        'action'     => 'error'
    ]
];
