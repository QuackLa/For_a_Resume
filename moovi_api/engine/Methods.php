<?php

namespace Moovi;

class Methods
{
    /**
     * Передача данных во вьюху
     */
    function view($object, $view, $array)
    {
        echo $object->run($view, $array);

        /*
        //Выводим на экран промежуточные результаты, если это требуется
        ob_flush();
        flush();
        */
    }


    /**
     * Дополнительные параметры curla, общие для всех методов
     */
    function curlOptions($curl, $options)
    {
        //Задаем дополнительные параметры
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-type: application/json"]); //тип данных == json
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($options));  //кодируем наш массив данных в json
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  //засовываем вывод ответа в curl_exec. Иначе вывод пойдёт сразу в браузер
    }


    /**
     * Получаем ID пользователя по его номеру договора
     */
    function getUser($url, $login, $pass, $contract)
    {
        //Подготавливаем массив данных для запроса
        $options = 
        [
            'account' => $login,
            'password' => $pass,
            'contract' => $contract,
        ];

        //Подготавливаем curl запрос, указыем нужный метод через url
        $curl = \curl_init($url.'/getUser');
        //Задаем дополнительные параметры
        $this->curlOptions($curl, $options);

        //Выполняем запрос к API, декодируем ответ от него, который был возвращён в exec
        $result = json_decode(curl_exec($curl));
        //Код полученного ответа
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        //Закрываем соединение с API
        curl_close($curl);

        //Разбираем результат ответа
        if($status == 200) //Если код будет 200, то мы ожидаем массив и форичим его
        {
            foreach($result as $key['id'] => $value)
                return $value;
        }
        elseif($status !== 200) //Любой код кроме 200 вернёт пустой массив. Там будет только код
            return false;
    }


    /**
     * Редактируем пользователя
     */
    function changeUser($url, $login, $pass, $id, $changeStatus)
    {
        //Подготавливаем массив данных для запроса
        $options = 
        [
            'account' => $login,
            'password' => $pass,
            'id' => $id,
            'status' => $changeStatus, // (active|paused|blocked)
        ];

        //Подготавливаем curl запрос, указыем нужный метод через url
        $curl = curl_init($url.'/changeUser');
        //Задаем дополнительные параметры
        $this->curlOptions($curl, $options);

        //Выполняем запрос к API, декодируем ответ от него, который был возвращён в exec
        $result = json_decode(curl_exec($curl));
        //Код полученного ответа
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        //Закрываем соединение с API
        curl_close($curl);

        //Разбираем результат ответа
        if($status == 200) //Если код будет 200, то мы ожидаем массив и форичим его
        {
            return true;
        }
        elseif($status !== 200) //Любой код кроме 200 вернёт пустой массив. Там будет только код
            return false;
    }
}