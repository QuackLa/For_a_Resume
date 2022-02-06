<?php

namespace DHCP;

class RepositoryConfig
{
    /**
     * Основной адрес с подразделом в репозитории
     */
    const GIT_URL = 'http://172.31.3.150/innoc/config/-/blob/master/servers/';

    /**
     * DHCP 172.31.0.2
     */
    const CUST_02 = '172.31.0.2/etc/dhcp/';

    /**
     * DHCP 172.31.0.4
     */
    const CUST_04 = '172.31.0.4/etc/dhcp/';

    /**
     * DHCP 172.31.1.3
     */
    const CUST_01 = '172.31.1.3/etc/dhcp/';

    /**
     * Возвразаем URL
     */
    public function getUrl()
    {
        return self::GIT_URL;
    }

    /**
     * Возвращаем поддиректорию в зависимости от запроса
     */
    public function getDirectory($direct)
    {
        //Ожидаем получить 1,2 или 4 в $direct
        switch($direct)
        {
            case 2:
                return self::CUST_02;
            case 4:
                return self::CUST_04;
            case 1:
                return self::CUST_01;
            default:
                break;
        }
    }

    /**
     * Возвращаем имя файла для составления полного пути при отправки в репозиторий
     */
    public function getFileName($name)
    {
        return "usr_$name.conf";
    }

    /**
     * Параметры курла
     */
    public function curlOptions($curl, $path, $file)
    {
        //Установка этого параметра в ненулевое значение означает, что будет производиться закачка файла на удаленный сервер.
        curl_setopt($curl, CURLOPT_UPLOAD, true);
        //true для загрузки файла методом HTTP PUT. Используемый файл должен быть установлен с помощью параметров CURLOPT_INFILE и CURLOPT_INFILESIZE.	
        curl_setopt($curl, CURLOPT_PUT, true);
        //Указываем из какого файла будем передавать информацию
        curl_setopt($curl, CURLOPT_INFILE, $file);
        //Указываем ожидаемый размер файла. Этот параметр не остановит передачу в случае превышения значения
        curl_setopt($curl, CURLOPT_INFILESIZE, filesize($path));
        //Помещаем вывод ответа в curl_exec. Иначе вывод пойдёт сразу в браузер
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    }

    /**
     * Запись в удалённый файл
     */
    public function writeFile($direct, $name, $path, $file)
    {
        //Составляем URL
        $url = $this->getUrl().$this->getDirectory($direct).$this->getFileName($name);
        //Инициируем CURL
        $curl = \curl_init($url);

        //Задаем дополнительные параметры
        $this->curlOptions($curl, $path, $file);

        //Код полученного ответа
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        //Закрываем работу с CURL
        curl_close($curl);
    }
}