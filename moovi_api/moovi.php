<?php

//Убираем лимит времени работы скриптов
set_time_limit(0);
ini_set('max_execution_time', '0');


//Подключаем классы
@include_once('config/Config.php');
@include_once('engine/Methods.php');
@include_once("vendor/autoload.php");


//Указываем пути к классам
use Moovi\Config;
use Moovi\Methods;
use eftec\bladeone\BladeOne;


/**
 * Создаём объекты классов
 */
$config = new Config();
$methods = new Methods();
$blade = new BladeOne();


//Получаем ID клиента по номеру его договора
$id = $methods->getUser($config->getURL(), $config->getLOGIN(), $config->getPASS(), $_POST['contract']);


//Если договор существует, то получив его ID пытаемся изменить статус аккаунта клиента
if($id)
{
    if($methods->changeUser($config->getURL(), $config->getLOGIN(), $config->getPASS(), $id, $_POST['action']))
        $makeChange = true;
}
//Если договор не найден и были заданы параметры поиска (номер договора и желаемый статус), то вываливаем ошибку
elseif($_POST['action'] and $_POST['contract'] and !$id)
    $error = true;


// Передаём нужные данные во вьюху
$methods->view($blade, "mainPage", [
    "error" => $error,
    "success" => $makeChange,
]);