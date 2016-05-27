<?php
/**
 * Phire Console Module
 *
 * @link       https://github.com/phirecms/phire-console
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Phire\Console\Module;

use Pop\Application;
use Pop\Db\Record;
use Pop\Console\Console;

/**
 * Console Module class
 *
 * @category   Phire\Console
 * @package    Phire\Console
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
class Module extends \Pop\Module\Module
{

    /**
     * Initialize the application
     *
     * @param  Application $application
     * @throws \Phire\Exception
     * @return Module
     */
    public function register(Application $application)
    {
        parent::register($application);

        // Set the database
        if ($this->application->services()->isAvailable('database')) {
            Record::setDb($this->application->getService('database'));
            $db = (count($this->application->getService('database')->getTables()) > 0);
        } else {
            $db = false;
        }
        $this->application->mergeConfig(['db' => $db]);

        // Check PHP version
        if (version_compare(PHP_VERSION, '5.4.0') < 0) {
            throw new \Phire\Exception('Error: Phire CMS requires PHP 5.4.0 or greater.');
        }

        // Add route params for the controllers
        if (null !== $this->application->router()) {
            $this->application->router()->addControllerParams(
                '*', [
                    'application' => $this->application,
                    'console'     => new Console(120, '    ')
                ]
            );
        }

        // Set up triggers to check the application session
        $this->application->on('app.route.post', 'Phire\Event\Db::check', 1000);
        $this->application->on('app.route.pre', function(){
            if (isset($_SERVER['argv'][1]) && (($_SERVER['argv'][1] != 'sql') && ($_SERVER['argv'][1] != 'archive'))) {
                echo PHP_EOL . '    Phire Console' . PHP_EOL;
                echo '    =============' . PHP_EOL . PHP_EOL;
            }
        }, 1000);
        $this->application->on('app.dispatch.post', function(){
            echo PHP_EOL;
        }, 1000);

        return $this;
    }

    /**
     * Error handler
     *
     * @param  \Exception $exception
     * @return void
     */
    public function error(\Exception $exception)
    {
        $message = strip_tags($exception->getMessage());
        if (stripos(PHP_OS, 'win') === false) {
            $string  = "    \x1b[1;37m\x1b[41m    " . str_repeat(' ', strlen($message)) . "    \x1b[0m" . PHP_EOL;
            $string .= "    \x1b[1;37m\x1b[41m    " . $message . "    \x1b[0m" . PHP_EOL;
            $string .= "    \x1b[1;37m\x1b[41m    " . str_repeat(' ', strlen($message)) . "    \x1b[0m" . PHP_EOL . PHP_EOL;
            $string .= "    Try \x1b[1;33m./phire help\x1b[0m for help" . PHP_EOL . PHP_EOL;
        } else {
            $string = $message . PHP_EOL . PHP_EOL;
            $string .= '    Try \'./phire help\' for help' . PHP_EOL . PHP_EOL;
        }

        echo $string;
        exit(127);
    }

}
