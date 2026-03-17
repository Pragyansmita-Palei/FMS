<!DOCTYPE html>
<html>
<head>
    <title>Products PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h3>Product List</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Store</th>
                <th>Name</th>
                <th>Group</th>
                <th>MRP</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->store->storename ?? '-' }}</td>
                <td>{{ $p->name }}</td>
                <td>{{ $p->group_type }}</td>
                <td>₹ {{ number_format($p->mrp, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
