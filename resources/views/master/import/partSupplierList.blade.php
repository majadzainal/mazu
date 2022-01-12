<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Part Supplier List</title>
</head>
<body>
    <table>
        <tr>
            <th>Part Supplier Name</th>
            <th>Part Supplier ID</th>
            <th>Is WIP</th>
            <th>Unit</th>
        </tr>
        @foreach ($partList as $ls)
        <tr>
            <td>{{ $ls->part_name." - ".$ls->part_number }}</td>
            <td>{{ $ls->part_supplier_id != '' ? $ls->part_supplier_id : $ls->part_customer_id}}</td>
            <td>{{ $ls->part_supplier_id != '' ? 0 : 1 }}</td>
            <td>{{ $ls->unit->unit_name }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
