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

    /**
     * On (activate) action method
     *
     * @return void
     */
    public function on()
    {
        $moduleId = $this->getModuleId();
        $module   = Table\Modules::findById($moduleId);
        if (isset($module->id)) {
            $module->active = 1;
            $module->save();
        }

        echo PHP_EOL . '    ' . $this->console->colorize('Module Activated!', Console::BOLD_GREEN);
    }

    /**
     * Off (de-activate) action method
     *
     * @return void
     */
    public function off()
    {
        $moduleId = $this->getModuleId();
        $module   = Table\Modules::findById($moduleId);
        if (isset($module->id)) {
            $module->active = 0;
            $module->save();
        }

        echo PHP_EOL . '    ' . $this->console->colorize('Module Deactivated!', Console::BOLD_YELLOW);
    }

    /**
     * Install action method
     *
     * @return void
     */
    public function install()
    {
        $module  = new Model\Module();
        $new     = $module->detectNew();

        if ($new > 0) {
            $module = new Model\Module();
            $module->install($this->services);
            echo '    ' . $this->console->colorize($new . ' Module' . (($new > 1) ? 's' : '') . ' Installed!', Console::BOLD_GREEN);
        } else {
            echo '    ' . $this->console->colorize('No new modules detected.', Console::BOLD_YELLOW);
        }
    }

    /**
     * Install action method
     *
     * @return void
     */
    public function remove()
    {
        $moduleId = $this->getModuleId();
        $module   = new Model\Module();
        $module->process(['rm_modules' => [$moduleId]], $this->services);
        echo PHP_EOL . '    ' . $this->console->colorize('Module removed.', Console::BOLD_RED);
    }

    /**
     * Get module id
     *
     * @return int
     */
    protected function getModuleId()
    {
        $modules   = Table\Modules::findAll()->rows();
        $module    = new Model\Module();
        $new       = $module->detectNew();
        $moduleIds = [];

        if ($new > 0) {
            echo '    [' . $new . ' New Module' . (($new > 1) ? 's' : '') . ' Detected]' . PHP_EOL . PHP_EOL;
        }

        echo "    ID  \tActive\t\tModule" . PHP_EOL;
        echo "    ----\t------\t\t------" . PHP_EOL;

        foreach ($modules as $module) {
            $moduleIds[] = $module->id;
            echo PHP_EOL . '    ' . $module->id . "\t" . (($module->active) ? 'Yes' : 'No') . "\t\t" . $module->folder;
        }

        echo PHP_EOL . PHP_EOL;

        $moduleId = null;
        while (!is_numeric($moduleId) || !in_array($moduleId, $moduleIds)) {
            $moduleId = $this->console->prompt('    Select Module ID: ');
        }

        return $moduleId;
    }

}
