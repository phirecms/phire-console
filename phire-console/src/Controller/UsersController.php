<?php

namespace Phire\Console\Controller;

use Phire\Model;
use Phire\Table;
use Pop\Console\Console;
use Pop\Validator\Email;

class UsersController extends ConsoleController
{

    /**
     * Index action method
     *
     * @return void
     */
    public function index()
    {
        $roleId = $this->getRoleId();

        $user = new Model\User();
        $users = $user->getAll($roleId);

        $this->console->append();
        $this->console->append("ID  \tUsername\tEmail");
        $this->console->append("----\t--------\t-----");

        foreach ($users as $user) {
            $this->console->append($user->id . "\t" . $user->username . "\t\t" . $user->email);
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
        $roleId   = $this->getRoleId();
        $username = '';
        $email    = null;
        $role     = new Model\Role();
        $role->getById($roleId);

        $this->console->write();
        $dupeUser = Table\Users::findBy(['username' => $username]);

        while (($username == '') || isset($dupeUser->id)) {
            if (isset($dupeUser->id)) {
                $this->console->write($this->console->colorize('That username already exists.', Console::BOLD_RED));
                $username = '';
            }
            if ($role->email_as_username) {
                while (!(new Email())->evaluate($username)) {
                    $username = $this->console->prompt($this->console->getIndent() . 'Enter Email: ');
                }
                $email = $username;
            } else {
                while ($username == '') {
                    $username = $this->console->prompt($this->console->getIndent() . 'Enter Username: ');
                }
                if ($role->email_required) {
                    $email = '';
                    while (!(new Email())->evaluate($email)) {
                        $email = $this->console->prompt($this->console->getIndent() . 'Enter Email: ');
                    }
                }
            }
            $dupeUser = Table\Users::findBy(['username' => $username]);
        }

        $password = '';
        while ($password == '') {
            $password = $this->console->prompt($this->console->getIndent() . 'Enter Password: ');
        }

        $active = '';
        while ((strtolower($active) != 'y') && (strtolower($active) != 'n')) {
            $active = $this->console->prompt($this->console->getIndent() . 'Active? (Y/N): ');
        }

        $verified = '';
        while ((strtolower($verified) != 'y') && (strtolower($verified) != 'n')) {
            $verified = $this->console->prompt($this->console->getIndent() . 'Verified? (Y/N): ');
        }

        $fields = [
            'role_id'   => $roleId,
            'username'  => $username,
            'password1' => $password,
            'email'     => $email,
            'active'    => (strtolower($active) == 'y') ? 1 : 0,
            'verified'  => (strtolower($verified) == 'y') ? 1 : 0
        ];

        $user = new Model\User();
        $user->save($fields);

        $this->console->write();
        $this->console->write($this->console->colorize('User Added!', Console::BOLD_GREEN));
    }

    /**
     * Password action method
     *
     * @return void
     */
    public function password()
    {
        $roleId = $this->getRoleId();

        $user    = new Model\User();
        $users   = $user->getAll($roleId);
        $userIds = [];

        $this->console->append();
        $this->console->append("ID  \tUsername\tEmail");
        $this->console->append("----\t--------\t-----");

        foreach ($users as $user) {
            $userIds[] = $user->id;
            $this->console->append($user->id . "\t" . $user->username . "\t\t" . $user->email);
        }

        $this->console->append();
        $this->console->send();

        $userId = null;
        while (!is_numeric($userId) || !in_array($userId, $userIds)) {
            $userId = $this->console->prompt($this->console->getIndent() . 'Select User ID: ');
        }

        $password = '';
        while ($password == '') {
            $password = $this->console->prompt($this->console->getIndent() . 'Enter New Password: ');
        }

        $user = new Model\User();
        $user->update([
            'id'        => $userId,
            'role_id'   => $roleId,
            'password1' => $password
        ]);

        $this->console->write();
        $this->console->write($this->console->colorize('User Password Updated!', Console::BOLD_GREEN));
    }

    /**
     * Remove action method
     *
     * @return void
     */
    public function remove()
    {
        $roleId = $this->getRoleId();

        $user = new Model\User();
        $users = $user->getAll($roleId);
        $userIds = [];

        $this->console->append();
        $this->console->append("ID  \tUsername\tEmail");
        $this->console->append("----\t--------\t-----");

        foreach ($users as $user) {
            $userIds[] = $user->id;
            $this->console->append($user->id . "\t" . $user->username . "\t\t" . $user->email);
        }

        $this->console->append();
        $this->console->send();

        $userId = null;
        while (!is_numeric($userId) || !in_array($userId, $userIds)) {
            $userId = $this->console->prompt($this->console->getIndent() . 'Select User ID: ');
        }

        $user = new Model\User();
        $user->remove(['rm_users' => [$userId]]);

        $this->console->write();
        $this->console->write($this->console->colorize('User Removed!', Console::BOLD_RED));
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
