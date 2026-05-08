<!DOCTYPE html>
<html>
<head>
    <title>Garden Compliance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 30px; }
        .status-good { color: green; font-weight: bold; }
        .status-violation { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌿 Community Garden Project</h1>
        <h2>Land Use Compliance Audit Report</h2>
        <p>Generated on: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Plot #</th>
                <th>Tenant</th>
                <th>Status</th>
                <th>Pests</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allInspections as $insp)
            <tr>
                <td>{{ $insp->created_at->format('d/m/Y') }}</td>
                <td>#{{ $insp->plot->plot_number }}</td>
                <td>{{ $insp->plot->user->name ?? 'N/A' }}</td>
                <td>
                    <span class="{{ $insp->status == 'good' || $insp->status == 'compliant' ? 'status-good' : 'status-violation' }}">
                        {{ ucfirst($insp->status) }}
                    </span>
                </td>
                <td>{{ $insp->has_pests ? 'Yes' : 'No' }}</td>
                <td>{{ $insp->notes }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>