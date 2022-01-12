<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Warehouse</title>
</head>
<body>
    <table>
        <tr>
            <th>Warehouse Name</th>
            <th>Warehouse ID</th>
            <th>Plant Name</th>
            <th>Plant ID</th>
        </tr>
        @foreach ($warehouseList as $ls)
        <tr>
            <td>{{ $ls->warehouse_name }}</td>
            <td>{{ $ls->warehouse_id }}</td>
            <td>{{ $ls->plant->plant_name }}</td>
            <td>{{ $ls->plant_id }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
