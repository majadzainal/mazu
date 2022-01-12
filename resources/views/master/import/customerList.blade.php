<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer List</title>
</head>
<body>
    <table>
        <tr>
            <th>Customer Name</th>
            <th>Customer ID</th>
        </tr>
        @foreach ($customerList as $ls)
        <tr>
            <td>{{ $ls->business_entity.". ".$ls->customer_name }}</td>
            <td>{{ $ls->customer_id }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
