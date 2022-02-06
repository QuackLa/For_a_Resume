<?php
        
// массив, в который данные будут сохраняться
$data = [];

// открываем файл
$openFile = fopen("test.csv", "r");

// убираем первую строчку
fgets($openFile);

// создаём массив ключей для будущего массива
$keys = 
[
    "id",
    "model",
    "name",
    "role",
    "distcrict",
    "street",
    "number_of_house",
    "entrance",
];

/**
 * Если файл не кончился и имеет формат csv, то перебираем файл в виде массива, значение делаем новым массивом из строки,
 * которая была поделена запятыми. Затем из массива ключей и нового массива создаёт финальный массив.
 */
while (!feof($openFile) and $openCSV = fgetcsv($openFile, 100, ",")) 
{
    foreach($openCSV as $key => $value)
    {
        $test[] = $value;
    }
}

fclose($openFile);
?>

<html>
<table border="1" width="100%">
<tr>
    <td>
        <!--telco_id,-->
        <!--network_type,-->
        switch_id,
        <!--begin_time,-->
        description,
        <!--switch_type,-->
        address_type,
        country,
        city,
        street,
        bulding
    </td>
</tr>
<tr>
    <? foreach($test as $k => $v): ?>
        <td> <?= $v ?> </td>
        </tr>
        <tr>
    <? endforeach ?>
</tr>
</table>
</html>