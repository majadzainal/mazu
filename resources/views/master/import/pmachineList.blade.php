<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Process Machine</title>
</head>
<body>
    <table>
        <tr>
            <th>Machine Code</th>
            <th>Machine ID</th>
        </tr>
        @foreach ($pmachineList as $ls)
        <tr>
            <td>{{ $ls->code }}</td>
            <td>{{ $ls->pmachine_id }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
