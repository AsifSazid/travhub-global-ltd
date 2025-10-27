<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hotel Lists</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f2f2f2; }
        .text-center{ text-align: center}
    </style>
</head>
<body>
    <h2>Hotel Lists</h2>
    <table>
        <thead>
            <tr>
                <th>Sl No.</th>
                <th>Hotel</th>
                <th>City</th>
                <th>Country</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hotels as $index => $hotel)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $hotel->title }}</td>
                    <td class="text-center">{{ $hotel->city_title }}</td>
                    <td class="text-center">{{ $hotel->city->country->title }}</td>
                    <td class="text-center">{{ $hotel->status === 'active' ? 'Active' : 'Inactive' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
