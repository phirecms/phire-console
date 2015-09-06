<?php

namespace Phire\Console\Controller;

use Phire\Model;
use Pop\Console\Console;

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

        echo PHP_EOL . "    ID  \tUsername\tEmail" . PHP_EOL;
        echo "    ----\t--------\t-----" . PHP_EOL;

        foreach ($users as $user) {
            echo PHP_EOL . '    ' . $user->id . "\t" . $user->username . "\t\t" . $user->email;
        }
    }

    /**
     * Add action method
     *
     * @return void
     */
    public function add()
    {
        $roleId = $this->getRoleId();

        $role = new Model\Role();
        $role->getById($roleId);

        if ($role->email_as_username) {
            $prompt = '    Enter Email: ';
        } else {
            $prompt = '    Enter Username: ';
        }

        echo PHP_EOL;

        $username = '';
        while ($username == '') {
            $username = $this->console->prompt($prompt);
        }

        $password = '';
        while ($password == '') {
            $password = $this->console->prompt('    Enter Password: ');
        }

        $active = '';
        while ((strtolower($active) != 'y') && (strtolower($active) != 'n')) {
            $active = $this->console->prompt('    Active? (Y/N): ');
        }

        $fields = [
            'role_id'   => $roleId,
            'username'  => $username,
            'password1' => $password,
            'active'    => (strtolower($active) == 'y') ? 1 : 0,
            'verified'  => 1,
        ];

        if ($role->email_as_username) {
            $fields['email'] = $username;
        }

        $user = new Model\User();
        $user->save($fields);

        echo PHP_EOL . '    ' . $this->console->colorize('User Added!', Console::BOLD_GREEN);
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

        echo PHP_EOL . "    ID  \tUsername\tEmail" . PHP_EOL;
        echo "    ----\t--------\t-----" . PHP_EOL;

        foreach ($users as $user) {
            $userIds[] = $user->id;
            echo PHP_EOL . '    ' . $user->id . "\t" . $user->username . "\t\t" . $user->email;
        }

        echo PHP_EOL . PHP_EOL;

        $userId = null;
        while (!is_numeric($userId) || !in_array($userId, $userIds)) {
            $userId = $this->console->prompt('    Select User ID: ');
        }

        $password = '';
        while ($password == '') {
            $password = $this->console->prompt('    Enter New Password: ');
        }

        $user = new Model\User();
        $user->update([
            'id'        => $userId,
            'role_id'   => $roleId,
            'password1' => $password
        ]);

        echo PHP_EOL . '    ' . $this->console->colorize('User Password Updated!', Console::BOLD_GREEN);
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

        echo PHP_EOL . "    ID  \tUsername\tEmail" . PHP_EOL;
        echo "    ----\t--------\t-----" . PHP_EOL;

        foreach ($users as $user) {
            $userIds[] = $user->id;
            echo PHP_EOL . '    ' . $user->id . "\t" . $user->username . "\t\t" . $user->email;
        }

        echo PHP_EOL . PHP_EOL;

        $userId = null;
        while (!is_numeric($userId) || !in_array($userId, $userIds)) {
            $userId = $this->console->prompt('    Select User ID: ');
        }

        $user = new Model\User();
        $user->remove(['rm_users' => [$userId]]);

        echo PHP_EOL . '    ' . $this->console->colorize('User Removed!', Console::BOLD_RED);
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
            echo '    ' . $role->name . ":\t" . $role->id . PHP_EOL;
        }

        echo PHP_EOL;

        $roleId = null;
        while (!is_numeric($roleId) || !in_array($roleId, $roleIds)) {
            $roleId = $this->console->prompt('    Select Role ID: ');
        }

        return $roleId;
    }

}
