<?php
/**
 * Module Name: phire-console
 * Author: Nick Sagona
 * Description: This is the console module for Phire CMS 2, to be used on the command-line
 * Version: 1.0
 */
return [
    'phire-console' => [
        'prefix'     => 'Phire\Console\\',
        'src'        => __DIR__ . '/../src',
        'routes'     => include 'routes.php',
        'install'    => function(){
            copy(__DIR__ . '/../script/phire', __DIR__ . '/../../../phire');
            copy(__DIR__ . '/../script/phire.bat', __DIR__ . '/../../../phire.bat');
            copy(__DIR__ . '/../script/phire.php', __DIR__ . '/../../../phire.php');
            chmod(__DIR__ . '/../../../phire', 0777);
            chmod(__DIR__ . '/../../../phire.bat', 0777);
            chmod(__DIR__ . '/../../../phire.php', 0777);
        },
        'uninstall' => function(){
            if (file_exists(__DIR__ . '/../../../phire')) {
                unlink(__DIR__ . '/../../../phire');
            }
            if (file_exists(__DIR__ . '/../../../phire.bat')) {
                unlink(__DIR__ . '/../../../phire.bat');
            }
            if (file_exists(__DIR__ . '/../../../phire.php')) {
                unlink(__DIR__ . '/../../../phire.php');
            }
        }
    ]
];
