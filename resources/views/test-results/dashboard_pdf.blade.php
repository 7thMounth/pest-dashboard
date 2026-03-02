<!DOCTYPE html>
<html>
<head>
    <title>Pest Dashboard Report</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #009688; padding-bottom: 10px; }
        .header h1 { color: #009688; margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        
        .stats-container { width: 100%; margin-bottom: 30px; }
        .stat-card { float: left; width: 23%; margin-right: 2%; padding: 15px 0; text-align: center; border-radius: 4px; color: white; }
        .stat-card:last-child { margin-right: 0; }
        .stat-card h3 { margin: 0; font-size: 18px; }
        .stat-card p { margin: 5px 0 0; font-size: 12px; opacity: 0.9; }
        
        .bg-red { background-color: #f44336; }
        .bg-blue { background-color: #2196F3; }
        .bg-teal { background-color: #009688; }
        .bg-orange { background-color: #ff9800; }
        
        .clear { clear: both; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f1f1f1; border: 1px solid #ddd; padding: 8px; text-align: left; }
        td { border: 1px solid #ddd; padding: 8px; }
        tr:nth-child(even) { background-color: #fafafa; }
        
        .status-tag { padding: 3px 6px; border-radius: 3px; font-size: 10px; color: white; }
        .tag-green { background-color: #4CAF50; }
        .tag-red { background-color: #f44336; }
        .tag-blue { background-color: #2196F3; }
        
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #eee; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEST DASHBOARD REPORT</h1>
        <p>Generated on {{ now()->format('M d, Y H:i:s') }}</p>
        @if($startDate || $endDate)
            <p>Period: {{ $startDate ?: 'Beginning' }} to {{ $endDate ?: 'Present' }}</p>
        @endif
    </div>

    <div class="stats-container">
        <div class="stat-card bg-red">
            <h3>{{ $totalTests }}</h3>
            <p>Total Tests</p>
        </div>
        <div class="stat-card bg-blue">
            <h3>{{ $failedTests }}</h3>
            <p>Failed Tests</p>
        </div>
        <div class="stat-card bg-teal">
            <h3>{{ number_format($avgResponseTime, 3) }}s</h3>
            <p>Avg Response</p>
        </div>
            <h3>{{ number_format($successRate, 2) }}%</h3>
        <div class="clear"></div>
    </div>

    <h2>Test Results Detailed Table</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>URL</th>
                <th>Method</th>
                <th>Status</th>
                <th>Success %</th>
                <th>Avg Time</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($testResults as $test)
                @php
                    $rate = $test->total_requests > 0 ? ($test->successful_requests / $test->total_requests) * 100 : 0;
                @endphp
                <tr>
                    <td>{{ $test->id }}</td>
                    <td>{{ $test->url }}</td>
                    <td>{{ $test->method }}</td>
                    <td>
                        <span class="status-tag {{ $test->status == 'completed' ? 'tag-green' : ($test->status == 'failed' ? 'tag-red' : 'tag-blue') }}">
                            {{ ucfirst($test->status) }}
                        </span>
                    </td>
                    <td>{{ number_format($rate, 2) }}%</td>
                    <td>{{ number_format($test->average_response_time, 3) }}s</td>
                    <td>{{ $test->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        &copy; {{ date('Y') }} Pest Dashboard. All rights reserved.
    </div>
</body>
</html>
