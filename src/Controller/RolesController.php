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
 * Console Roles Controller class
 *
 * @category   Phire\Console
 * @package    Phire\Console
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2016 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.phirecms.org/license     New BSD License
 * @version    1.0.0
 */
class RolesController extends ConsoleController
{

    /**
     * Index action method
     *
     * @return void
     */
    public function index()
    {
        $roles = (new Model\Role())->getAll();

        $this->console->append("ID  \tName");
        $this->console->append("----\t----");

        foreach ($roles as $role) {
            $this->console->append($role->id . "\t" . $role->name);
        }

        $this->console->send();
    }

    /**
     * Add action method
     *
     * @return void
     */
    public function add()
    {
        $name = '';
        while ($name == '') {
            $name = $this->console->prompt($this->console->getIndent() . 'Enter Name: ', null, true);
        }

        $verification = '';
        while ((strtolower($verification) != 'y') && (strtolower($verification) != 'n')) {
            $verification = $this->console->prompt($this->console->getIndent() . 'Verification? (Y/N) ');
        }

        $approval = '';
        while ((strtolower($approval) != 'y') && (strtolower($approval) != 'n')) {
            $approval = $this->console->prompt($this->console->getIndent() . 'Approval? (Y/N) ');
        }

        $emailAsUsername = '';
        while ((strtolower($emailAsUsername) != 'y') && (strtolower($emailAsUsername) != 'n')) {
            $emailAsUsername = $this->console->prompt($this->console->getIndent() . 'Email as Username? (Y/N): ');
        }

        $emailRequired = '';
        while ((strtolower($emailRequired) != 'y') && (strtolower($emailRequired) != 'n')) {
            $emailRequired = $this->console->prompt($this->console->getIndent() . 'Email Required? (Y/N): ');
        }

        $fields = [
            'role_parent_id'    => '----',
            'name'              => $name,
            'verification'      => (strtolower($verification) == 'y') ? 1 : 0,
            'approval'          => (strtolower($approval) == 'y') ? 1 : 0,
            'email_as_username' => (strtolower($emailAsUsername) == 'y') ? 1 : 0,
            'email_required'    => (strtolower($emailRequired) == 'y') ? 1 : 0
        ];

        $role = new Model\Role();
        $role->save($fields);

        $this->console->write();
        $this->console->write($this->console->colorize('Role Added!', Console::BOLD_GREEN));
    }

    /**
     * Password action method
     *
     * @return void
     */
    public function edit()
    {
        $roleId = $this->getRoleId();

        $name = '';
        while ($name == '') {
            $name = $this->console->prompt($this->console->getIndent() . 'Enter Name: ', null, true);
        }

        $verification = '';
        while ((strtolower($verification) != 'y') && (strtolower($verification) != 'n')) {
            $verification = $this->console->prompt($this->console->getIndent() . 'Verification? (Y/N) ');
        }

        $approval = '';
        while ((strtolower($approval) != 'y') && (strtolower($approval) != 'n')) {
            $approval = $this->console->prompt($this->console->getIndent() . 'Approval? (Y/N) ');
        }

        $emailAsUsername = '';
        while ((strtolower($emailAsUsername) != 'y') && (strtolower($emailAsUsername) != 'n')) {
            $emailAsUsername = $this->console->prompt($this->console->getIndent() . 'Email as Username? (Y/N): ');
        }

        $emailRequired = '';
        while ((strtolower($emailRequired) != 'y') && (strtolower($emailRequired) != 'n')) {
            $emailRequired = $this->console->prompt($this->console->getIndent() . 'Email Required? (Y/N): ');
        }

        $fields = [
            'id'                => $roleId,
            'role_parent_id'    => '----',
            'name'              => $name,
            'verification'      => (strtolower($verification) == 'y') ? 1 : 0,
            'approval'          => (strtolower($approval) == 'y') ? 1 : 0,
            'email_as_username' => (strtolower($emailAsUsername) == 'y') ? 1 : 0,
            'email_required'    => (strtolower($emailRequired) == 'y') ? 1 : 0
        ];

        $role = new Model\Role();
        $role->update($fields);

        $this->console->write();
        $this->console->write($this->console->colorize('Role Updated!', Console::BOLD_GREEN));
    }

    /**
     * Remove action method
     *
     * @return void
     */
    public function remove()
    {
        $roleId = $this->getRoleId();

        $role = new Model\Role();
        $role->remove(['rm_roles' => [$roleId]]);

        $this->console->write();
        $this->console->write($this->console->colorize('Role Removed!', Console::BOLD_RED));
    }

    /**
     * Get role id
     *
     * @return int
     */
    protected function getRoleId()
    {
        $roles   = (new Model\Role())->getAll();
        $roleIds = [];
        foreach ($roles as $role) {
            $roleIds[] = $role->id;
            $this->console->append($role->name . ":\t" . $role->id);
        }

        $this->console->append();
        $this->console->send();

        $roleId = null;
        while (!is_numeric($roleId) || !in_array($roleId, $roleIds)) {
            $roleId = $this->console->prompt($this->console->getIndent() . 'Select Role ID: ');
        }

        return $roleId;
    }

}
