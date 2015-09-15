<?php

namespace Phire\Console\Controller;

use Pop\Console\Console;

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
