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
        echo '    Phire CMS ' . \Phire\Module::VERSION;
    }

}
