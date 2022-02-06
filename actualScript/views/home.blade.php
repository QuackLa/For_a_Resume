<!doctype html>
<head>
    <meta charset="utf-8">
    <!-- Styles -->
    <link href="views/css/main.css" rel="stylesheet">
</head>

<body>
<table class="table">

    @if($errorConnect)
    <tr class='errorConnect'>
        <td> {{ $errorConnect }} </td>
    </tr>
    @endif

    @if($errorLogin)
    <tr class='errorLogin'>
        <td> {{ $errorLogin }} </td>
    </tr>
    @endif

    @if($errorCommands)
    <tr class='errorCommands'>
        <td> {{ $errorCommands }} </td>
    </tr>
    @endif


    @if($success)
    <tr class='success'>
        <td> {{ $success }} </td>
    </tr>
    @endif

    @if($search)
    <tr class='search'>
        <td> {{ $search }} </td>
    </tr>
    @endif

    @if($endScript)
    <tr class='endScript'>
        <td> {{ $endScript }} </td>
    </tr>
    @endif

</table>
</body>
</html>