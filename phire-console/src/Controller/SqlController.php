<?php

namespace Phire\Console\Controller;

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
     * @return void
     */
    public function cli()
    {
        echo '    SQL Cli';
    }

    /**
     * SQL Dump action method
     *
     * @return void
     */
    public function dump()
    {
        echo '    SQL dump';
    }

}
