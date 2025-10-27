<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Current Rate Lists</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f2f2f2; }
        .text-center{ text-align: center}
    </style>
</head>
<body>
    <h2>Current Rates</h2>
    <table>
        <thead>
            <tr>
                <th>Sl No.</th>
                <th>Current Rate</th>
                <th>Current Rate</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($current_rates as $index => $current_rate)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $current_rate->title }}</td>
                    <td class="text-center">{{ $current_rate->rate_value }}</td>
                    <td class="text-center">{{ $current_rate->status === 'active' ? 'Active' : 'Inactive' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
