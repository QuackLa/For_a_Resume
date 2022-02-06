<?php
//Открываем сессию. В сессии храним номер выбранного файла конфига
session_start();

/* Если потребуется убрать ограничение длительности выполнения скрипта
 set_time_limit(0);
 ini_set('max_execution_time', '0');
*/

//Подключаем файлы
@include_once('config/Arrays.php');
@include_once('config/RepositoryConfig.php');
@include_once('engine/CRUD.php');
@include_once('engine/ViewConnect.php');
@include_once("vendor/autoload.php");

//Указание путей к классам
use DHCP\Arrays;
use DHCP\RepositoryConfig;
use DHCP\CRUD;
use DHCP\ViewConnect;
use eftec\bladeone\BladeOne;

/**
 * Объекты классов с переменными, массивами, функциями
 */
$arrays = new Arrays();
$repo = new RepositoryConfig();
$CRUD = new CRUD();
$view = new ViewConnect();
$blade = new BladeOne();

//Переменные полученные из формы от пользователя
$district = $_POST['district']; //Район
$search = $_POST['search'];  //Иищем
$all = $_POST['all']; //Показать всё
$changing = $_POST['changing']; //Что меняем
$changeTo = $_POST['changeTo']; //На что меняем
$delete = $_POST['delete']; //Удаляем
$create = $_POST['create']; //Добавляем


//Получаем имя файла
if($district)
{
    $fileName = $CRUD->makeFileName($district, $arrays->forSelectDisctrict, __DIR__);
}

//Добавление строк
if($create)
{
    if($CRUD->create($fileName, $create))
    {
        $message = 'Данные добавлены!';
    }
}

//Изменение строк
if($changing and $changeTo)
{
    if($CRUD->update($fileName, $changing, $changeTo))
    {
        $message = 'Данные изменены!';
    }
}

//Удаление строк
if($delete)
{
    if($CRUD->delete($fileName, $delete))
    {
        $message = 'Удаление произведено!';
    }
}

//Поиск конкретных вещей
if($search)
{
    $linesResult = $CRUD->search($fileName, $search);
}

//Всё содержимое выбранного файла
if($all)
{
    $linesResult = $CRUD->showLines($fileName);
    $rangeResult = $CRUD->showRange($fileName);
}

//Переход ко вьюхе
$view->view($blade, "dhcp", [
    'select' => $arrays->forSelectDisctrict,
    'name' => $_SESSION['fileDistrict'],
    'range' => $rangeResult,
    'lines' => $linesResult,
    'message' => $message,
]);