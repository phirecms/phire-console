<?php

namespace Phire\Console\Controller;

use Phire\Table;
use Phire\Model;
use Pop\Console\Console;

class ModulesController extends ConsoleController
{

    /**
     * Index action method
     *
     * @return void
     */
    public function index()
    {
        $modules = Table\Modules::findAll()->rows();
        $module  = new Model\Module();
        $new     = $module->detectNew();

        if ($new > 0) {
            echo '    [' . $new . ' New Module' . (($new > 1) ? 's' : '') . ' Detected]' . PHP_EOL . PHP_EOL;
        }

        echo "    ID  \tActive\t\tModule" . PHP_EOL;
        echo "    ----\t------\t\t------" . PHP_EOL;

        foreach ($modules as $module) {
            echo PHP_EOL . '    ' . $module->id . "\t" . (($module->active) ? 'Yes' : 'No') . "\t\t" . $module->folder;
        }
    }

}
