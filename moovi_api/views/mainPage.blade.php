<!doctype html>
<head>
    <meta charset="utf-8">
    <!-- Styles -->
    <link href="views/css/main.css" rel="stylesheet">
</head>

<body>
<table class="table">
    @if($error)
        <tr>
            <td> Указанный договор не найден! </td>
        </tr>
    @endif

    @if($success)
        <tr>
            <td> Изменения успешно применены! </td>
        </tr>
    @endif

    <form method="POST" action="moovi.php">
        <table>
            <tr>
                <td>Номер договора:</td>
                <td>Действие:</td>
            </tr>
            <tr>
                <td><input name="contract" type="text"></td>
                <td>
                    <select class="action" name="action">
                        <option value="active">Включить</option>
                        <option value="blocked">Отключить</option>
                    </select>
                </td>
            </tr>
        </table>
        <div><input id="submit" type="submit"></div>
    </form>

</table>
</body>
</html>