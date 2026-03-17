<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Associates</title>

    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>

<h3>Sales Associates</h3>

<table>
    <thead>
        <tr>
            <th>Sales ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>City</th>
            <th>State</th>
        </tr>
    </thead>
    <tbody>
        @foreach($salesAssociates as $sa)
            <tr>
                <td>{{ $sa->sales_id }}</td>
                <td>{{ $sa->user?->name }}</td>
                <td>{{ $sa->user?->email }}</td>
                <td>{{ $sa->phone }}</td>
                <td>{{ $sa->city }}</td>
                <td>{{ $sa->state }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
