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
            $this->console->append('[' . $new . ' New Module' . (($new > 1) ? 's' : '') . ' Detected]' . PHP_EOL);
        }

        $this->console->append("ID  \tActive\t\tModule");
        $this->console->append("----\t------\t\t------");

        foreach ($modules as $module) {
            $this->console->append($module->id . "\t" . (($module->active) ? 'Yes' : 'No') . "\t\t" . $module->folder);
        }

        $this->console->send();
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

        $this->console->append();
        $this->console->append($this->console->colorize('Module Activated!', Console::BOLD_GREEN));
        $this->console->send();
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

        $this->console->append();
        $this->console->append($this->console->colorize('Module Deactivated!', Console::BOLD_YELLOW));
        $this->console->send();
    }

    /**
     * Install action method
     *
     * @return void
     */
    public function install()
    {
        $module = new Model\Module();
        $new    = $module->detectNew();

        if ($new > 0) {
            $module = new Model\Module();
            $module->install($this->services);
            $this->console->append($this->console->colorize(
                $new . ' Module' . (($new > 1) ? 's' : '') . ' Installed!', Console::BOLD_GREEN)
            );
        } else {
            $this->console->append(
                $this->console->colorize('No new modules detected.', Console::BOLD_YELLOW)
            );
        }

        $this->console->send();
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
        $this->console->append();
        $this->console->append($this->console->colorize('Module removed.', Console::BOLD_RED));
        $this->console->send();
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
            $this->console->append('[' . $new . ' New Module' . (($new > 1) ? 's' : '') . ' Detected]' . PHP_EOL);
        }

        $this->console->append("ID  \tActive\t\tModule");
        $this->console->append("----\t------\t\t------");

        foreach ($modules as $module) {
            $moduleIds[] = $module->id;
            $this->console->append($module->id . "\t" . (($module->active) ? 'Yes' : 'No') . "\t\t" . $module->folder);
        }

        $this->console->append();
        $this->console->send();

        $moduleId = null;
        while (!is_numeric($moduleId) || !in_array($moduleId, $moduleIds)) {
            $moduleId = $this->console->prompt($this->console->getIndent() . 'Select Module ID: ');
        }

        return $moduleId;
    }

}
