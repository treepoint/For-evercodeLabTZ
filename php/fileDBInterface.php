<?php

class fileDBInterface
{

    //$string Должна быть в формате: Поле1::Поле2::Поле3
    //$fileName - "имя таблицы", в данном случае файла
    public function insertIntoTable($filename, $string)
    {
        if (!file_exists($filename)) {
            return false;
        }

        $fp = fopen($filename, 'a');

        fputs($fp, $string . "\n");

        unset($fp);

        return true;
    }

    /* $fileName - "имя таблицы", в данном случае файла
     * $fieldNumber - номер поля по которому пойдет поиск
     * $searchString - строка, которую ищем */
    public function selectByFieldFromTable($fileName, $fieldNumber,
        $searchString
    ) {
        //Сначала получаем всю таблицу
        $array = array();

        if (!file_exists($fileName)) {
            return $array[] = 'Таблица\файл не найдена';
        }

        $file = file($fileName);

        for ($i = count($file) - 1; $i >= 0; $i--) {
            //Получаем всю строку
            $row = explode('::', trim($file[$i]));

            //сначала нужно проверить, что кол-во столбцов в строке не меньше чем
            //столбец который нам передали
            $count = count($row);
            if ($count < $fieldNumber) {
                return $array[] = 'Передан несуществующий столбец';
            }

            //Если нужное нам поле совпадает с переданным шаблоном, то добавляем
            //строку в массив
            if ($row[$fieldNumber - 1] == $searchString) {
                $id = array_shift($row);
                $array[$id] = $row;
            }
        }
        return $array;
    }

    //$string Должна быть в формате: Поле1::Поле2::Поле3
    //$fileName - "имя таблицы", в данном случае файла
    public
    function selectAllFromTable($fileName
    ) {
        $outputArray = array();

        if (!file_exists($fileName)) {
            return $outputArray[] = 'Таблица\файл не найдена';
        }

        $file = file($fileName);

        for ($i = count($file) - 1; $i >= 0; $i--) {
            //Получаем всю строку
            $row = explode('::', trim($file[$i]));
            //Получаем т.н PK - первый столбец
            $id = array_shift($row);
            //Добавляем в выходной массив все данные и используем PK как ключ
            $outputArray[$id] = $row;
        }

        return $outputArray;
    }

    //$string Должна быть в формате: Поле1::Поле2::Поле3
    //$fileName - "имя таблицы", в данном случае файла
    public
    function deleteFromTable($fileName, $id
    ) {
        if (!file_exists($fileName)) {
            return false;
        }

        $array = $this->selectAllFromTable($fileName);

        if (!array_key_exists($id, $array)) {
            return false;
        }

        unset($array[$id]);

        $this->_truncateTable($fileName);
        $this->_insertArrayIntoTable($fileName, $array);

        return true;
    }

    //$fileName - "имя таблицы", в данном случае файла
    //$array - массив который запихнем в эту таблицу
    private
    function _insertArrayIntoTable($fileName, $array
    ) {
        $this->_truncateTable($fileName);

        foreach ($array as $key => $value) {
            $this->insertIntoTable(
                $fileName, $key . '::' . implode('::', $value)
            );
        }
        return true;
    }

    //$fileName - "имя таблицы", в данном случае файла
    private
    function _createTable($fileName
    ) {
        fopen($fileName, 'w');
        return true;
    }

    //$fileName - "имя таблицы", в данном случае файла
    private
    function _deleteTable($fileName
    ) {
        return unlink($fileName);
    }

    //$fileName - "имя таблицы", в данном случае файла
    //По сути удаляет файл и создает снова
    private
    function _truncateTable($fileName
    ) {
        $this->_deleteTable($fileName);
        $this->_createTable($fileName);
        return true;
    }
}