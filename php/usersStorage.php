<?php

require_once './auth.php';
require_once './fileDBInterface.php';
require_once './jsonStatuses.php';
require_once './categoryStorage.php';

class usersStorage
{
    private $path = '../db/users.db'; // "таблица" с пользователями

    private function _isSessionExists()
    {
        $auth = new auth();
        return $auth->isSessionExists();
    }

    public function add($login, $password)
    {
        $JN = new jsonStatuses();

        $DB = new fileDBInterface();

        //time() - Доморошенная секвенция
        $string = time() . '::' . $login . '::' . md5($password);

        return $JN->boolToStatus($DB->insertIntoTable($this->path, $string));
    }

    public function remove($id, $login)
    {
        $JN = new jsonStatuses();

        if (!$this->_isSessionExists()) {
            return $JN->sessionNotExists();
        }

        session_start();

        if ($login != $_SESSION['login']) {
            return $JN->userNotMatch();
        }

        $DB = new fileDBInterface();

        return $JN->boolToStatus($DB->deleteFromTable($this->path, $id));
    }

    public function getAll()
    {
        $DB = new fileDBInterface();

        return json_encode($DB->selectAllFromTable($this->path));
    }

    public function getUserByLogoPass($login, $password)
    {
        $DB = new fileDBInterface();

        $array = $DB->selectAllFromTable($this->path);

        foreach ($array as $key => $value) {
            if ($value == array($login, md5($password))) {
                return true;
            }
        }
        return false;
    }
}

