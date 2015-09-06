<?php

return [
    '<controller> [<action>]' => [
        'prefix' => 'Phire\Console\Controller\\'
    ],
    '*' => [
        'controller' => 'Phire\Console\Controller\ConsoleController',
        'action'     => 'error'
    ]
];
