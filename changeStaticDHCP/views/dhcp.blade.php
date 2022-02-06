<!doctype html>
<head>
    <meta charset="utf-8">
    <!-- Styles -->
    <link href="views/css/dhcp.css" rel="stylesheet">
</head>

<body>

<table>
<form autocomplete="off" method="POST" action="index.php">
    <tr>
        <td><label>Район</label><td>
    </tr>
    <tr>
        <td>
            @if($select)
            <select name="district">
                @foreach($select as $k => $v)
                    <option value="{{ $k }}" @if($name == $k) selected="selected" @endif> {{ $k }} </option>
                @endforeach
            </select>
            @endif
        </td>
    </tr>
    <tr>
        <td><label>Найти</label><td>
    </tr>
    <tr>
        <td><input name="search" type="text" placeholder="Номер договора / IP-адресс"></td>
    </tr>
        <td>
            <button>Выполнить</button>
            <button name="all" value='all'>Показать всё содержимое файла</button><hr>
        </td>
    </tr>
    <tr>
        <td><label>Изменить</label>
    </tr>
    <tr>
        <td><input name="changing" type="text" placeholder="Строка или её часть, которую хотим заменить"></td>
    </tr>
    <tr>
        <td><input name="changeTo" type="text" placeholder="На что хотим заменить"></td>
    </tr>
        <td><button>Выполнить</button><hr></td>
    </tr>
    <tr>
        <td><label>Удалить</label></td>
    </tr>
    <tr>
        <td><input name="delete" type="text" placeholder="Введите содержимое строки или какую-то её часть"></td>
    </tr>
    <tr>
        <td><button>Выполнить</button><hr></td>
    </tr>
    <tr>
        <td><label>Создать</label></td>
    </tr>
    <tr>
        <td><input name="create" type="text" placeholder="Введите содержимое строки или какую-то её часть"></td>
    </tr>
    <tr>
        <td><button>Выполнить</button></td>
    </tr>
</form>
</table>

@if($message or $range or $lines)
    <div class="container">
@endif

@if($message)
<table>
    <tr>
        <td> {{ $message }} </td>
    </tr>
</table>
@endif

@if($range and is_array($range))
<label>Рейнджи адресов:</label>
<table>
    <tr>
        @foreach($range as $key => $value)
            <td> {{ $value }} </td>
        </tr>
        <tr>
        @endforeach
    </tr>
</table>
<br>
@endif

@if($lines and is_array($lines))
<label>Список абонентских записей:</label>
<table>
    <tr>
        @foreach($lines as $keygen => $val)
            <td> {{ $val }} </td>
        </tr>
        <tr>
        @endforeach
    </tr>
</table>
@endif

@if($message or $range or $lines)
    </div>
@endif

</body>

</html>