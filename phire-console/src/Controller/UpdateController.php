<?php

namespace Phire\Console\Controller;

use Phire\Model;
use Pop\Console\Console;

class UpdateController extends ConsoleController
{

    /**
     * Update index action method
     *
     * @return void
     */
    public function index()
    {
        $config  = new Model\Config();
        $updates = $config->getUpdates();

        if (version_compare($updates->phirecms, \Phire\Module::VERSION) == 0) {
            $this->console->append($this->console->colorize(
                $updates->phirecms . ' is available for update.', Console::BOLD_YELLOW
            ));
            $this->console->append();
            $this->console->append($this->console->colorize(
                'Please back up all of your files and your database before proceeding.', Console::BOLD_RED
            ));
            $this->console->append();
            $this->console->send();

            $update = null;
            while ((strtolower($update) != 'y') && (strtolower($update) != 'n')) {
                $update = $this->console->prompt($this->console->getIndent() . 'Update the system? (Y/N) ');
            }

            if (strtolower($update) == 'y') {
                if (is_writable(__DIR__ . '/../../../../../') && is_writable(__DIR__ . '/../../../../..' . APP_PATH)) {
                    $baseUpdater = new \Phire\BaseUpdater();
                    $baseUpdater->getUpdate();

                    clearstatcache();

                    $updater = new \Phire\Updater();
                    $updater->runPost();

                    $this->console->append();
                    $this->console->append($this->console->colorize(
                        'Update completed successfully!', Console::BOLD_CYAN
                    ));
                    $this->console->append($this->console->colorize(
                        'You have updated to Phire version ' . \Phire\Module::VERSION . '.', Console::BOLD_CYAN
                    ));
                    $this->console->send();
                } else {
                    $this->console->append();
                    $this->console->append($this->console->colorize(
                        'The system folders are not writable. They must be writable to update the system.', Console::BOLD_RED
                    ));
                    $this->console->send();
                }
            }
        } else {
            $this->console->append($this->console->colorize('System is up-to-date!', Console::BOLD_GREEN));
            $this->console->send();
        }
    }

}
