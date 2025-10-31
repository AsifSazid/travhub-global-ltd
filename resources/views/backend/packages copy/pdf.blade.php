<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Package Lists</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f2f2f2; }
        .text-center{ text-align: center}
    </style>
</head>
<body>
    <h2>Package Lists</h2>
    <table>
        <thead>
            <tr>
                <th>Sl No.</th>
                <th>Package</th>
                <th>Package Code</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($packages as $index => $package)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $package->title }}</td>
                    <td class="text-center">{{ $package->country_code }}</td>
                    <td class="text-center">{{ $package->status === 'active' ? 'Active' : 'Inactive' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
