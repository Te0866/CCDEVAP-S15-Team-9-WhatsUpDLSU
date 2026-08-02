<?php
require_once __DIR__ . '/../models/UserModel.php';


class AccountController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // Builds data for Account Management table
    public function listAccounts($search = null, $type = null) {
        $accounts = $this->userModel->getManagedAccounts($search, $type);
        $rows = [];
        foreach ($accounts as $account) {
            $isOrganization = $account['ROLE'] === 'OFFICER';

            $rows[] = [
                'user_id' => (int) $account['USER_ID'],
                'type'    => $isOrganization ? 'organization' : 'student',
                'name'    => $account['USER_NAME'],
                'created_at' => $account['CREATED_AT'],
                'status'  => $account['STATUS'],
            ];
        }

        return $rows;
    }

    // Loads data for Create/Edit Officer form
    public function getOrganizationFormData($userId = null) {
        if ($userId === null) {
            return ['mode' => 'create', 'userId' => null, 'orgName' => '', 'password' => ''];
        }

        $user = $this->userModel->getUserById($userId);

        if (!$user || $user['ROLE'] !== 'OFFICER') {
            return ['mode' => 'create', 'userId' => null, 'orgName' => '', 'password' => ''];
        }

        return [
            'mode'    => 'edit',
            'userId'  => (int) $user['USER_ID'],
            'orgName' => $user['USER_NAME'],
            'password'=> $user['PASSWORD'],
        ];
    }

    // Creates or updates an Officer account
    public function saveOrganization($userId, $orgName, $password) {
        $orgName = trim($orgName);
        $password = trim($password);

        if ($orgName === '' || $password === '') {
            return ['success' => false, 'error' => 'Organization name and password are required.'];
        }

        $isEdit = $userId !== null && $userId !== '';

        if ($isEdit) {
            $userId = (int) $userId;

            if ($this->userModel->usernameExists($orgName, $userId)) {
                return ['success' => false, 'error' => 'That organization name is already taken.'];
            }

            $this->userModel->updateUser($userId, $orgName, $password);

            return ['success' => true, 'error' => null];
        }

        // Create new officer
        if ($this->userModel->usernameExists($orgName)) {
            return ['success' => false, 'error' => 'That username is already taken.'];
        }

        $this->userModel->createUser($orgName, $password, 'OFFICER');
        return ['success' => true, 'error' => null];
    }

    // Loads data for Add/Edit Student form
    public function getUserFormData($userId = null) {
        if ($userId === null) {
            return ['mode' => 'create', 'userId' => null, 'username' => '', 'password' => ''];
        }

        $user = $this->userModel->getUserById($userId);

        if (!$user || $user['ROLE'] !== 'USER') {
            return ['mode' => 'create', 'userId' => null, 'username' => '', 'password' => ''];
        }

        return [
            'mode'     => 'edit',
            'userId'   => (int) $user['USER_ID'],
            'username' => $user['USER_NAME'],
            'password' => $user['PASSWORD'],
        ];
    }

    // Creates or updates a student account.
    public function saveUser($userId, $username, $password) {
        $username = trim($username);
        $password = trim($password);

        if ($username === '' || $password === '') {
            return ['success' => false, 'error' => 'Username and password are required.'];
        }

        $isEdit = $userId !== null && $userId !== '';

        if ($isEdit) {
            $userId = (int) $userId;

            if ($this->userModel->usernameExists($username, $userId)) {
                return ['success' => false, 'error' => 'That username is already taken.'];
            }

            $this->userModel->updateUser($userId, $username, $password);
            return ['success' => true, 'error' => null];
        }

        if ($this->userModel->usernameExists($username)) {
            return ['success' => false, 'error' => 'That username is already taken.'];
        }

        $this->userModel->createUser($username, $password, 'USER');
        return ['success' => true, 'error' => null];
    }

    // Deletes a student or officer account
    public function deleteAccount($type, $id) {
        $id = (int) $id;
        return $this->userModel->deleteUser($id);
    }
}
