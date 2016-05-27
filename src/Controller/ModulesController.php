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

use Phire\Table;
use Phire\Model;
use Pop\Console\Console;

/**
 * Console Modules Controller class
 *
 * @category   Phire\Console
 * @package    Phire\Console
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
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
     * Update action method
     *
     * @return void
     */
    public function update()
    {
        $config   = new Model\Config();
        $updates  = $config->getUpdates();
        $moduleId = $this->getModuleId();
        $module   = Table\Modules::findById($moduleId);

        if (isset($module->id)) {
            if (isset($updates->modules[$module->folder]) &&
                (version_compare($updates->modules[$module->folder], $module->version) < 0)) {
                $this->console->append();
                $this->console->append($this->console->colorize(
                    'The \'' . $module->folder .'\' module is available for update.', Console::BOLD_YELLOW
                ));
                $this->console->append();
                $this->console->append($this->console->colorize(
                    'Please back up all of your files and your database before proceeding.', Console::BOLD_RED
                ));
                $this->console->append();
                $this->console->send();

                $update = null;
                while ((strtolower($update) != 'y') && (strtolower($update) != 'n')) {
                    $update = $this->console->prompt(
                        $this->console->getIndent() . 'Update the \'' . $module->folder .'\' module? (Y/N) '
                    );
                }

                if (strtolower($update) == 'y') {
                    if (is_writable(__DIR__ . '/../../../') &&
                        is_writable(__DIR__ . '/../../../' . $module->folder) &&
                        is_writable(__DIR__ . '/../../../' . $module->folder . '.zip')) {
                        $updater = new \Phire\Updater($module->folder);
                        $updater->getUpdate($module->folder);

                        clearstatcache();

                        $updaterClass = $module->prefix . 'Updater';

                        if (class_exists($updaterClass)) {
                            $updater = new $updaterClass($module->folder);
                            $updater->runPost();
                        } else if (file_exists(__DIR__ . '/../../../' . $module->folder . '/src/Updater.php')) {
                            include __DIR__ . '/../../../' . $module->folder . '/src/Updater.php';
                            if (class_exists($updaterClass)) {
                                $updater = new $updaterClass($module->folder);
                                $updater->runPost();
                            }
                        } else {
                            $module->updated_on = date('Y-m-d H:i:s');
                            $module->save();
                        }

                        $this->console->append();
                        $this->console->append($this->console->colorize(
                            'Update completed successfully!', Console::BOLD_CYAN
                        ));
                        $this->console->append($this->console->colorize(
                            'You have updated \'' . $module->folder . '\' to version ' . $updates->modules[$module->folder] . '.', Console::BOLD_CYAN
                        ));
                        $this->console->send();
                    } else {
                        $this->console->append();
                        $this->console->append($this->console->colorize(
                            'The module folders are not writable. They must be writable to update the module.', Console::BOLD_RED
                        ));
                        $this->console->send();
                    }
                }
            } else {
                $this->console->append();
                $this->console->append($this->console->colorize(
                    'The \'' . $module->folder .'\' module is up-to-date!', Console::BOLD_GREEN
                ));
                $this->console->send();
            }
        }
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
