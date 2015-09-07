<?php

namespace Phire\Console\Controller;

use Pop\Console\Console;

class HelpController extends ConsoleController
{

    /**
     * Help action method
     *
     * @return void
     */
    public function index()
    {
        $y = Console::BOLD_YELLOW;
        $g = Console::BOLD_GREEN;

        echo '    ./phire ' . $this->console->colorize('help', $y) . "\t\tShow this help screen" . PHP_EOL;
        echo '    ./phire ' . $this->console->colorize('config', $y) . "\t\tShow the current system configuration" . PHP_EOL;
        echo '    ./phire ' . $this->console->colorize('version', $y) . "\t\tShow the current version" . PHP_EOL;

        if (stripos(PHP_OS, 'win') === false) {
            echo '    ./phire ' . $this->console->colorize('archive', $y) . "\t\tArchive the current system and content" . PHP_EOL . PHP_EOL;
        } else {
            echo PHP_EOL;
        }

        echo '    ./phire ' . $this->console->colorize('users', $y) . "\t\tList users" . PHP_EOL;
        echo '    ./phire ' . $this->console->colorize('users', $y) . ' ' . $this->console->colorize('add', $g) . "\t\tAdd a user" . PHP_EOL;
        echo '    ./phire ' . $this->console->colorize('users', $y) . ' ' . $this->console->colorize('password', $g) . "\tChange a user password" . PHP_EOL;
        echo '    ./phire ' . $this->console->colorize('users', $y) . ' ' . $this->console->colorize('remove', $g) . "\tRemove a user" . PHP_EOL . PHP_EOL;

        echo '    ./phire ' . $this->console->colorize('modules', $y) . "\t\tList modules" . PHP_EOL;
        echo '    ./phire ' . $this->console->colorize('modules', $y) . ' ' . $this->console->colorize('install', $g) . "\tInstall a module or modules" . PHP_EOL;
        echo '    ./phire ' . $this->console->colorize('modules', $y) . ' ' . $this->console->colorize('on', $g) . "\t\tActivate a module" . PHP_EOL;
        echo '    ./phire ' . $this->console->colorize('modules', $y) . ' ' . $this->console->colorize('off', $g) . "\t\tDeactivate a module" . PHP_EOL;
        echo '    ./phire ' . $this->console->colorize('modules', $y) . ' ' . $this->console->colorize('remove', $g) . "\tRemove a module";

        if (stripos(PHP_OS, 'win') === false) {
            echo PHP_EOL . PHP_EOL;
            echo '    ./phire ' . $this->console->colorize('sql', $y) . ' ' . $this->console->colorize('cli', $g) . "\t\tOpen SQL client" . PHP_EOL;
            echo '    ./phire ' . $this->console->colorize('sql', $y) . ' ' . $this->console->colorize('dump', $g) . "\t\tDump the database";
        }
    }

}

