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

use Phire\Model;
use Pop\Console\Console;

/**
 * Console Config Controller class
 *
 * @category   Phire\Console
 * @package    Phire\Console
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
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
