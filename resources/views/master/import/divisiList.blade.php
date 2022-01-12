<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Divisi</title>
</head>
<body>
    <table>
        <tr>
            <th>Divisi Name</th>
            <th>ID</th>
        </tr>
        @foreach ($divisiList as $ls)
        <tr>
            <td>{{ $ls->divisi_name }}</td>
            <td>{{ $ls->divisi_id }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
