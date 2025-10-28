<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Activity Lists</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f2f2f2; }
        .text-center{ text-align: center}
    </style>
</head>
<body>
    <h2>Activity Lists</h2>
    <table>
        <thead>
            <tr>
                <th>Sl No.</th>
                <th>Activity</th>
                <th>Country</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activities as $index => $activity)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $activity->title }}</td>
                    <td class="text-center">{{ $activity->country_title }}</td>
                    <td class="text-center">{{ $activity->status === 'active' ? 'Active' : 'Inactive' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
