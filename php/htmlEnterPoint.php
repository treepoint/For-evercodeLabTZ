<?php

require_once './goodsStorage.php';
require_once './categoryStorage.php';
require_once './auth.php';

$usersStorage = new usersStorage();
$goodsStorage = new goodsStorage();
$categoryStorage = new categoryStorage();
$auth = new auth();

//region Auth
if ($_POST['login']) {
    echo $auth->login($_POST['loginText'], $_POST['passwordText']);
}

if ($_POST['logoff']) {
    echo $auth->logoff();
}

if ($_POST['getAllUsers']) {
    $Array = $usersStorage->getAll();
    echo $Array;
}

//endregion

//region Goods
if ($_POST['addGood']) {
    echo $goodsStorage->add(
        $_POST["name"], $_POST["category"], $_POST["cost"]
    );
}

if ($_POST['removeGood']) {
    echo($goodsStorage->remove(
        $_POST["idGoodToRemove"]
    ));
}

if ($_POST['getAllGoods']) {
    $goodsArray = $goodsStorage->getAll();
    echo $goodsArray;
}

if ($_POST['getGoodsByCategory']) {
    $goodsArray = $goodsStorage->getAllByCategory(
        $_POST['fieldNumber'], $_POST['searchString']
    );
    echo $goodsArray;
}
//endregion

//region Categories
if ($_POST['getAllCategories']) {
    $Array = $categoryStorage->getAll();
    echo $Array;
}

if ($_POST['removeCategory']) {
    echo($categoryStorage->remove(
        $_POST["idCategoryToRemove"]
    ));
}

if ($_POST['addCategory']) {
    echo $categoryStorage->add(
        $_POST["name"]
    );
}
//endregion