<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Customers</title>

    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #ccc; padding:6px; }
        th { background:#f2f2f2; }
    </style>
</head>
<body>

<h3>Customer List</h3>

<table>
    <thead>
    <tr>
        <th>Code</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Orders</th>
        <th>Join Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($customers as $c)
        <tr>
            <td>{{ $c->customer_code }}</td>
            <td>{{ $c->name }}</td>
            <td>{{ $c->phone }}</td>
            <td>{{ $c->email }}</td>
            <td>{{ $c->projects_count }}</td>
            <td>{{ $c->created_at->format('Y-m-d') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
