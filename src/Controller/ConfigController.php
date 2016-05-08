<?php

namespace Phire\Console\Controller;

use Phire\Model;
use Pop\Console\Console;

class ConfigController extends ConsoleController
{

    /**
     * Config index action method
     *
     * @return void
     */
    public function index()
    {
        $config = new Model\Config();

        foreach ($config->overview as $key => $value) {
            if (($key != 'domain') && ($key != 'document_root')) {
                $this->console->write(
                    $this->console->colorize(
                        ucwords(str_replace(['_', 'php'], [' ', 'PHP'], $key)), Console::BOLD_GREEN
                    ) . ': ' . $value
                );
            }
        }
    }

}
