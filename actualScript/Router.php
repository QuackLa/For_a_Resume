<?php

/**
 * Обход коммутаторов по списку и применение на них команд
 */

set_time_limit(0);
ini_set('max_execution_time', '0');

@include_once('config/Config.php');
@include_once('engine/Functions.php');
@include_once('engine/Arrays.php');
@include_once("vendor/autoload.php");

use Work\Config;
use Work\Functions;
use Work\Arrays;
use eftec\bladeone\BladeOne;

/**
 * Объекты классов с переменными, массивами, функциями
 */
$config = new Config();
$functions = new Functions();
$arrays = new Arrays();
$blade = new BladeOne();


/**
 * ---------------------- !!!NJTICE!!! -----------------------------------------------------------------------------------------------------------
 * connect(хост, предельное время на работу с одним хостом в секундах)
 * auth($connect, логин, пароль, вход в прив. режим, пароль для прив. режима)
 * writeCommands($connect, команда/ы, пауза для выполнения основной команды в милисекундах)
 * readCommands($connect, цель поиска, длинна читаемого отрезка, отступ от начала файла в байтах)
 * write($connect)
 * reload($connect) reloadAfter($connect, время до ребута)
 * logout($connect)
 * closeConnect($connect)
 * endOfScript(Массив хостов, ключ перебора) - встраивается в foreach(), в конце
 */


foreach($arrays->CISCO_3550 as $key => $host)
{
    // Создаём подключение
    $connect = $functions->connect($host, $config->endOfLiveConnect);
    //echo $functions->writeCommands($connect, $config->getTimer(), $config->pause);

    if($connect === false)
    {
        $functions->view($blade, "home", ["errorConnect" => "$host не пингуется"]);
    }
    
    // Если соединение успешно
    if(@$connect)
    {
        // Вводим лог и пасс и заходим в прив. режим
        if($functions->auth($connect, $config->login, $config->pass, $config->enable, $config->enablePass) === false)
        {
            $functions->view($blade, "home", ["errorLogin" => "Не удалось авторизоваться на хосте $host"]);
        }

        // Выполняем ряд нужных команд
        if($functions->writeCommands($connect, $config->commands, $config->pause) === false)
        {
            $functions->view($blade, "home", ["errorCommands" => "Список основных команд пуст!"]);
        }

        // Читаем команды и ищем нужное в них. На основе находится что-то делаем (это надо добавлять)
        /*
        if($functions->readCommands($connect, $config->target, $config->lenght, $config->indent))
        {
            $search = "Нашлось";
        }
        else
        {
            $search = "Не нашлось";
        }
        */

        sleep(1);
        //sleep(70);

        // Ставим отложенный ребут
        //$functions->reloadAfter($connect, $config->reloadDelay);

        // Сохраняем конфиг
        $functions->write($connect);
        
        // Выходим со свитча
        $functions->logout($connect);
        // Закрываем соединение
        $functions->closeConnect($connect);

        // Передаём нужные данные во вьюху
        $functions->view($blade, "home", [
            "success" => "Работа с хостом $host завершена",
            "search" => $search,
        ]);
    }

    // Сообщение об окончания списка и работы скрипта
    if($functions->endOfScript($arrays->CISCO_3550, $key))
    {
        $functions->view($blade, "home", ["endScript" => "Работа скрипта закончена!"]);
    }
}