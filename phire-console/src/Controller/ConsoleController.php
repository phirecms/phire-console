<?php

namespace Phire\Console\Controller;

use Pop\Application;
use Pop\Console\Console;
use Pop\Service\Locator;

class ConsoleController extends \Pop\Controller\AbstractController
{


    /**
     * Application object
     * @var Application
     */
    protected $application = null;

    /**
     * Services locator
     * @var Locator
     */
    protected $services = null;

    /**
     * Console object
     * @var \Pop\Console\Console
     */
    protected $console = null;

    /**
     * Config object
     * @var \ArrayObject
     */
    protected $config = null;

    /**
     * Constructor for the controller
     *
     * @param  Application $application
     * @param  Console     $console
     * @return self
     */
    public function __construct(Application $application, Console $console)
    {
        $this->application = $application;
        $this->services    = $application->services();
        $this->console     = $console;

        if ($this->services->isAvailable('database')) {
            $this->config = (new \Phire\Model\Config())->getAll();
        }
    }

    /**
     * Get application object
     *
     * @return Application
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * Get services object
     *
     * @return Locator
     */
    public function services()
    {
        return $this->services;
    }

    /**
     * Get request object
     *
     * @return Console
     */
    public function console()
    {
        return $this->console;
    }

    /**
     * Get config object
     *
     * @return \ArrayObject
     */
    public function config()
    {
        return $this->config;
    }

    /**
     * Help action method
     *
     * @return void
     */
    public function help()
    {
        echo 'Help!' . PHP_EOL;
    }

    /**
     * Default error action method
     *
     * @return void
     */
    public function error()
    {
        $this->send();
    }

    /**
     * Send response
     *
     * @param  string $body
     * @return void
     */
    public function send($body = null)
    {
        $this->application->trigger('app.send.pre', ['controller' => $this]);

        /*
        if (null !== $body) {
            $this->response->setBody($body);
        } else if (null !== $this->view) {
            $this->response->setBody($this->view->render());
        }
        */

        $this->application->trigger('app.send.post', ['controller' => $this]);

        //$this->response->send($code, $headers);
    }

}
