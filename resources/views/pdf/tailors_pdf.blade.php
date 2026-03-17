<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tailors</title>

    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #000; padding:5px; }
        th { background:#f2f2f2; }
    </style>
</head>
<body>

<h3>Tailor List</h3>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>Tailor ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>City</th>
        <th>State</th>
        <th>Pin</th>
    </tr>
    </thead>
    <tbody>
    @foreach($tailors as $i => $tailor)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $tailor->tailor_id }}</td>
            <td>{{ $tailor->user->name ?? '' }}</td>
            <td>{{ $tailor->user->email ?? '' }}</td>
            <td>{{ $tailor->phone }}</td>
            <td>{{ $tailor->city }}</td>
            <td>{{ $tailor->state }}</td>
            <td>{{ $tailor->pin }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
