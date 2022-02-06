<?php

namespace DHCP;

class CRUD
{
    /**
     * Создание адреса файла
     */
    public function makeFileName($district, $data, $root)
    {
        //Записываем полученое значение в сессию, чтобы выбор района не скидывался каждый раз
        $districtName = $_SESSION['fileDistrict'] = $district;
    
        //Сравниваем имя района и полученные данные. Если совпадают, то берём имя файла соответствующего району и создаём имя файла
        foreach($data as $name => $nameOfFile)
        {
            if($name == $districtName)
            {
                return $root."/data/$nameOfFile.txt";
            }
        }
    }

    /**
     * Открываем файл
     */
    public function openFile($fileName)
    {
        return @fopen($fileName, "r");
    }

    /**
     * Завершаем работу с файлом
     */
    public function closeFile($openFile)
    {
        return @fclose($openFile);
    }

    /**
     * Создание новой строки
     */
    public function create($fileName, $create)
    {
        if(file_put_contents($fileName, "\n$create", FILE_APPEND | LOCK_EX))
            return true;
    }

    /**
     * Изменение/замена строк
     */
    public function update($fileName, $changing, $changeTo)
    {
        $changeCont = file_get_contents($fileName);
        $changeCont = str_replace($changing, $changeTo, $changeCont);

        if(file_put_contents($fileName, $changeCont))
            return true;
    }

    /**
     * Удаление строк
     */
    public function delete($fileName, $delete)
    {
        $delContent = file_get_contents($fileName);
        $delContent = str_replace("\n$delete", '', $delContent);
        
        if(file_put_contents($fileName, $delContent))
            return true;
    }

    /**
     * Поиск отдельных строк
     */
    public function search($fileName, $search)
    {
        return $this->parseFile($fileName, $search);
    }

    /**
     * Рейндж адресов
     */
    public function showRange($fileName)
    {
        return $this->parseFile($fileName, 'range');
    }

    /**
     * Выборка всех строк со статикой в файле
     */
    public function showLines($fileName)
    {
        return $this->parseFile($fileName, 'host');
    }

    /**
     * Парсинг файла и поиск в нём нужного
     */
    public function parseFile($fileName, $search)
    {
        //Открываем файл
        @$openFile = $this->openFile($fileName);

        //Создаём переменную для складирования данных.
        $file = [];

        //Читаем весь файл, пока он не кончится
        while(($line = fgets($openFile, 4096)) !== false)
        {
            //Если файл не пустой и что-то считалось
            if(!empty($line))
            {
                //Разделяем полученный список по строкам и каждая строка станет элементом массива
                $elements = explode("\r\n", $line);
            
                //Проверяемся, что получился массив
                if(array($elements))
                {
                    //Разбираем массив
                    foreach($elements as $key => $value)
                    {
                        //Рейнджи адресов
                        if(preg_match("/$search/", $value))
                        {
                            $file[] = $value;
                        }
                    }
                }
            }
        }//конец while

        //Закрываем файл
        $this->closeFile($openFile);

        //Возвращаем массив данных
        return $file;
    }
}