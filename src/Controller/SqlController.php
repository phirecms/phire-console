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

/**
 * Console Sql Controller class
 *
 * @category   Phire\Console
 * @package    Phire\Console
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
class SqlController extends ConsoleController
{

    /**
     * SQL Index action method
     *
     * @return void
     */
    public function index()
    {
        $this->error();
    }

    /**
     * SQL CLI action method
     *
     * @param  string $type
     * @throws \Phire\Exception
     * @return void
     */
    public function cli($type = null)
    {
        if (stripos(PHP_OS, 'win') !== false) {
            throw new \Phire\Exception('This option is not available on Windows.');
        } else {
            if (null !== $type) {
                switch ($type) {
                    case 'mysql':
                        echo '--database=' . DB_NAME . ' --user=' . DB_USER . ' --password=' . DB_PASS . ' --host=' . DB_HOST;
                        break;
                    case 'pgsql':
                        echo '--dbname=' . DB_NAME . ' --username=' . DB_USER;
                        break;
                    case 'sqlite':
                        echo DB_NAME;
                        break;
                }

            } else {
                echo (DB_INTERFACE == 'pdo') ? DB_TYPE : DB_INTERFACE;
            }
        }
    }

    /**
     * SQL Dump action method
     *
     * @param  string $type
     * @throws \Phire\Exception
     * @return void
     */
    public function dump($type = null)
    {
        if (stripos(PHP_OS, 'win') !== false) {
            throw new \Phire\Exception('This option is not available on Windows.');
        } else {
            if (null !== $type) {
                switch ($type) {
                    case 'mysql':
                        echo '--user=' . DB_USER . ' --password=' . DB_PASS . ' --host=' . DB_HOST . ' ' . DB_NAME .
                            ' > ' . DB_NAME . '_' . date('Y-m-d') . '.mysql.sql';
                        break;
                    case 'pgsql':
                        echo '--username=' . DB_USER . ' ' . DB_NAME .
                            ' > ' . DB_NAME . '_' . date('Y-m-d') . '.pgsql.sql';
                        break;
                    case 'sqlite':
                        echo DB_NAME . ' .dump > phirecms_' . date('Y-m-d') . '.sqlite.sql';
                        break;
                }

            } else {
                echo (DB_INTERFACE == 'pdo') ? DB_TYPE : DB_INTERFACE;
            }
        }
    }

    /**
     * SQL Dump action method for mysql
     *
     * @return void
     */
    public function mysql()
    {
        $this->dump('mysql');
    }

    /**
     * SQL Dump action method for pgsql
     *
     * @return void
     */
    public function pgsql()
    {
        $this->dump('pgsql');
    }

    /**
     * SQL Dump action method for sqlite
     *
     * @return void
     */
    public function sqlite()
    {
        $this->dump('sqlite');
    }

}
