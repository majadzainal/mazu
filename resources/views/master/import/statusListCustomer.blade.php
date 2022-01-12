<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Status Part Supplier</title>
</head>
<body>
    <table>
        <tr>
            <th>Status Name</th>
            <th>Status ID</th>
        </tr>
        @foreach ($statusList as $ls)
        <tr>
            <td>{{ $ls->status }}</td>
            <td>{{ $ls->status_id }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
