<?php
/**
 * phire-console main CLI PHP script
 */

if ((stripos(php_sapi_name(), 'cli') === false) || (stripos(php_sapi_name(), 'server') !== false)) {
    header('Content-Type: text/html');
    echo '<!DOCTYPE html>' . PHP_EOL .
        '<html><head><title>HTTP 403 (Forbidden)</title>' .
        '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><meta name="robots" content="none" /></head>' .
        '<body><h1>HTTP 403 (Forbidden)</h1></body></html>';
    exit();
}

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
    $autoloader = require APP_ABS_PATH . '/vendor/autoload.php';
    $appConfig  = include APP_ABS_PATH . '/config/application.php';
    $config     = include MODULES_PATH . '/phire-console-1.0/config/module.php';

    $config['phire-console']['services'] = $appConfig['services'];
    $config['phire-console']['routes']   = $config['phire-console']['cli-routes'];

    $autoloader->addPsr4($config['phire-console']['prefix'], $config['phire-console']['src']);

    // Create and run the app as a self-contained console app
    $app = new Pop\Application($autoloader, $config['phire-console']);
    $app->register('phire', new Phire\Console\Module\Module($app));

    if (!(\Phire\Table\Modules::findBy(['name' => 'phire-console'])->active)) {
        $app->trigger('app.route.pre');
        throw new \Phire\Exception('The Phire Console module is not active.');
    }

    $app->run();
} catch (Exception $exception) {
    $phire = new Phire\Console\Module\Module();
    $phire->error($exception);
}
