<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Country Lists</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f2f2f2; }
        .text-center{ text-align: center}
    </style>
</head>
<body>
    <h2>Country Lists</h2>
    <table>
        <thead>
            <tr>
                <th>Sl No.</th>
                <th>Country</th>
                <th>Country Code</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($countries as $index => $country)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $country->title }}</td>
                    <td class="text-center">{{ $country->country_code }}</td>
                    <td class="text-center">{{ $country->is_active ? 'Active' : 'Inactive' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
