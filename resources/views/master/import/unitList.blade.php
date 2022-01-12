<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Unit</title>
</head>
<body>
    <table>
        <tr>
            <th>Unit Name</th>
            <th>Unit ID</th>
        </tr>
        @foreach ($unitList as $ls)
        <tr>
            <td>{{ $ls->unit_name }}</td>
            <td>{{ $ls->unit_id }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
