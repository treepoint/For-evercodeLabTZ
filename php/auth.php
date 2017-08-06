<?php

require_once './usersStorage.php';
require_once './jsonStatuses.php';

class auth
{
    public function login($login, $password)
    {
        $US = new usersStorage();
        $JN = new jsonStatuses();

        if (!$login or !$password) {
            return $JN->inputParametersInvalid();
        }

        if ($this->isSessionExists()) {
            return $JN->sessionAlreadyOpen();
        }

        if ($US->getUserByLogoPass($login, $password)) {
            return $JN->boolToStatus($this->startSession($login));
        }

        return false;
    }
    public function logoff() {
        $JN = new jsonStatuses();

        if (!$this->isSessionExists()) {
            return $JN->sessionNotExists();
        }

        return $JN->boolToStatus($this->closeSession());
    }

    public function startSession($login)
    {
        session_start();
        $_SESSION['authorized']=true;
        $_SESSION['login'] = $login;
        return $_SESSION['authorized'];
    }

    public function closeSession()
    {
        session_start();

        if (!$_SESSION['authorized']) {
            return false;
        }

        unset($_SESSION['authorized']);
        unset($_SESSION['login']);
        return true;
    }

    public function isSessionExists()
    {
        session_start();
        return $_SESSION['authorized'];
    }
}