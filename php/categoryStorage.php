<?php

require_once './auth.php';
require_once './fileDBInterface.php';
require_once './jsonStatuses.php';

class categoryStorage
{
    private $path = '../db/goodsCategory.db'; //"Таблица" категорий

    private function _isSessionExists()
    {
        $auth = new auth();
        return $auth->isSessionExists();
    }

    public function isCategoryExists($name)
    {
        if (!$name) {
            return false;
        }

        $DB = new fileDBInterface();

        $categoriesArray = $DB->selectAllFromTable($this->path);

        foreach ($categoriesArray as $key => $value) {
            if (in_array($name, $value)) {
                return true;
            }
        }

        return false;
    }

    public function add($name)
    {
        $JN = new jsonStatuses();

        if (!$this->_isSessionExists()) {
            return $JN->sessionNotExists();
        }

        $DB = new fileDBInterface();

        //time() - Доморошенная секвенция
        $string = time() . '::' . $name;

        return $JN->boolToStatus($DB->insertIntoTable($this->path, $string));
    }

    public function remove($id)
    {
        $JN = new jsonStatuses();

        if (!$this->_isSessionExists()) {
            return $JN->sessionNotExists();
        }

        $DB = new fileDBInterface();

        return $JN->boolToStatus($DB->deleteFromTable($this->path, $id));
    }

    public function getAll()
    {
        $DB = new fileDBInterface();

        return json_encode($DB->selectAllFromTable($this->path));
    }
}

