#!/usr/bin/php
<?php
/**
 * phire-console main CLI PHP script
 */

require_once __DIR__  . '/../config.php';

set_time_limit(0);

try {
    // Check the app constants
    if (!defined('BASE_PATH') || !defined('APP_PATH') || !defined('APP_URI') ||
        !defined('DB_INTERFACE') || !defined('DB_NAME')) {
        throw new \Exception(
            'Error: The config file is not properly configured. Please check the config file or install the system.'
        );
    }

    // Get the autoloader
    $autoloader = require __DIR__ . '/../' . APP_PATH . '/vendor/autoload.php';
    $appConfig  = include __DIR__ . '/../' . APP_PATH . '/config/application.php';
    $config     = include MODULES_PATH . '/phire-console/config/module.php';

    $config['phire-console']['services'] = $appConfig['services'];

    $autoloader->addPsr4($config['phire-console']['prefix'], $config['phire-console']['src']);

    // Create and run the app as a self-contained console app
    $app = new Pop\Application($autoloader, $config['phire-console']);
    $app->register('phire', new Phire\Console\Module\Module($app))
        ->run();
} catch (Exception $exception) {
    $phire = new Phire\Console\Module\Module();
    $phire->error($exception);
}
