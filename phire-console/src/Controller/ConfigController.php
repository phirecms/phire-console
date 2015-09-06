<?php

namespace Phire\Console\Controller;

use Phire\Model;

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
                echo '    ' . ucwords(str_replace(['_', 'php'], [' ', 'PHP'], $key)) . ': ' . $value . PHP_EOL;
            }
        }
    }

}
