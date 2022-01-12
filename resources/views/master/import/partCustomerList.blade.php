<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Part Customer List</title>
</head>
<body>
    <table>
        <tr>
            <th>Part Customer Name</th>
            <th>Part Customer ID</th>
            <th>Unit</th>
        </tr>
        @foreach ($partCustomerList as $ls)
        <tr>
            <td>{{ $ls->part_name." - ".$ls->part_number }}</td>
            <td>{{ $ls->part_customer_id }}</td>
            <td>{{ $ls->unit->unit_name }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
