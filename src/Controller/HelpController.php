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
 * Console Help Controller class
 *
 * @category   Phire\Console
 * @package    Phire\Console
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
class HelpController extends ConsoleController
{

    /**
     * Help action method
     *
     * @return void
     */
    public function index()
    {
        $yellow = Console::BOLD_YELLOW;
        $green = Console::BOLD_GREEN;

        $this->console->append('./phire ' . $this->console->colorize('help', $yellow) . "\t\tShow this help screen");
        $this->console->append('./phire ' . $this->console->colorize('config', $yellow) . "\t\tShow the current system configuration");
        $this->console->append('./phire ' . $this->console->colorize('version', $yellow) . "\t\tShow the current version");
        $this->console->append('./phire ' . $this->console->colorize('update', $yellow) . "\t\tUpdate the system");

        if (stripos(PHP_OS, 'win') === false) {
            $this->console->append('./phire ' . $this->console->colorize('archive', $yellow) . "\t\tArchive the current system and content");
        }

        $this->console->append();
        $this->console->append('./phire ' . $this->console->colorize('modules', $yellow) . "\t\tList modules");
        $this->console->append('./phire ' . $this->console->colorize('modules', $yellow) . ' ' . $this->console->colorize('install', $green) . "\tInstall a module or modules");
        $this->console->append('./phire ' . $this->console->colorize('modules', $yellow) . ' ' . $this->console->colorize('on', $green) . "\t\tActivate a module");
        $this->console->append('./phire ' . $this->console->colorize('modules', $yellow) . ' ' . $this->console->colorize('off', $green) . "\t\tDeactivate a module");
        $this->console->append('./phire ' . $this->console->colorize('modules', $yellow) . ' ' . $this->console->colorize('remove', $green) . "\tRemove a module");
        $this->console->append('./phire ' . $this->console->colorize('modules', $yellow) . ' ' . $this->console->colorize('update', $green) . "\tUpdate a module");

        $this->console->append();
        $this->console->append('./phire ' . $this->console->colorize('users', $yellow) . "\t\tList users");
        $this->console->append('./phire ' . $this->console->colorize('users', $yellow) . ' ' . $this->console->colorize('add', $green) . "\t\tAdd a user");
        $this->console->append('./phire ' . $this->console->colorize('users', $yellow) . ' ' . $this->console->colorize('password', $green) . "\tChange a user password");
        $this->console->append('./phire ' . $this->console->colorize('users', $yellow) . ' ' . $this->console->colorize('activate', $green) . "\tActivate a user");
        $this->console->append('./phire ' . $this->console->colorize('users', $yellow) . ' ' . $this->console->colorize('deactivate', $green) . "\tDeactivate a user");
        $this->console->append('./phire ' . $this->console->colorize('users', $yellow) . ' ' . $this->console->colorize('remove', $green) . "\tRemove a user");

        $this->console->append();
        $this->console->append('./phire ' . $this->console->colorize('roles', $yellow) . "\t\tList roles");
        $this->console->append('./phire ' . $this->console->colorize('roles', $yellow) . ' ' . $this->console->colorize('add', $green) . "\t\tAdd a role");
        $this->console->append('./phire ' . $this->console->colorize('roles', $yellow) . ' ' . $this->console->colorize('edit', $green) . "\t\tEdit a role");
        $this->console->append('./phire ' . $this->console->colorize('roles', $yellow) . ' ' . $this->console->colorize('remove', $green) . "\tRemove a role");

        if (stripos(PHP_OS, 'win') === false) {
            $this->console->append();
            $this->console->append('./phire ' . $this->console->colorize('sql', $yellow) . ' ' . $this->console->colorize('cli', $green) . "\t\tOpen SQL client");
            $this->console->append('./phire ' . $this->console->colorize('sql', $yellow) . ' ' . $this->console->colorize('dump', $green) . "\t\tDump the database");
        }

        $this->console->send();
    }

}

