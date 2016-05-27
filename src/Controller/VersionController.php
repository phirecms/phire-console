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
namespace Phire\Console\Controller;

use Pop\Console\Console;

/**
 * Console Version Controller class
 *
 * @category   Phire\Console
 * @package    Phire\Console
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
class VersionController extends ConsoleController
{

    /**
     * Help action method
     *
     * @return void
     */
    public function index()
    {
        $this->console->write(
            'Phire CMS ' . $this->console->colorize(\Phire\Module::VERSION, Console::BOLD_CYAN)
        );
    }

}
