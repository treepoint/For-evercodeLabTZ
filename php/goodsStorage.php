<?php

require_once './auth.php';
require_once './fileDBInterface.php';
require_once './jsonStatuses.php';
require_once './categoryStorage.php';

class goodsStorage
{
    private $path = '../db/goods.db'; // "Таблица" товаров

    private function _isSessionExists()
    {
        $auth = new auth();
        return $auth->isSessionExists();
    }

    public function add($name, $categoryName, $cost)
    {
        $JN = new jsonStatuses();

        if (!$this->_isSessionExists()) {
            return $JN->sessionNotExists();
        }

        $CS = new categoryStorage();

        if (!$CS->isCategoryExists($categoryName)) {
            return $JN->categoryNotExists();
        }

        $DB = new fileDBInterface();

        //time() - Доморошенная секвенция
        $string = time() . '::' . $name . '::' . $categoryName . '::' . $cost;

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

    public function getAllByCategory($fieldNumber, $searchString)
    {
        $DB = new fileDBInterface();

        return json_encode($DB->selectByFieldFromTable($this->path, $fieldNumber, $searchString));
    }
}

