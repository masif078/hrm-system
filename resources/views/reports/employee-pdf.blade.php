<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Employees Report</title>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; }
        th { background: #eeeeee; }
        h2 { text-align: center; }
    </style>
</head>
<body>

    <h2>Employee Report</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>Designation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->id }}</td>
                    <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->department->name ?? '-' }}</td>
                    <td>{{ $employee->designation->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
