<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Supplier List</title>
</head>
<body>
    <table>
        <tr>
            <th>Supplier Name</th>
            <th>Supplier ID</th>
        </tr>
        @foreach ($supplierList as $ls)
        <tr>
            <td>{{ $ls->business_entity.". ".$ls->supplier_name }}</td>
            <td>{{ $ls->supplier_id }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
