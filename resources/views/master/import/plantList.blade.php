<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Plant</title>
</head>
<body>
    <table>
        <tr>
            <th>Plant Name</th>
            <th>Plant ID</th>
        </tr>
        @foreach ($plantList as $ls)
        <tr>
            <td>{{ $ls->plant_name }}</td>
            <td>{{ $ls->plant_id }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
