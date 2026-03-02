<!DOCTYPE html>
<html>
<head>
    <title>Test Result #{{ $testResult->id }} Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; margin: 0; padding: 20px; line-height: 1.6; }
        .header { border-bottom: 2px solid #009688; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { color: #009688; margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; font-size: 14px; }
        
        .section { margin-bottom: 25px; }
        .section-title { font-size: 18px; color: #009688; border-left: 4px solid #009688; padding-left: 10px; margin-bottom: 15px; background: #f0fdf4; padding-top: 5px; padding-bottom: 5px; }
        
        .grid-container { width: 100%; display: table; border-spacing: 10px; margin: 0 -10px; }
        .grid-item { display: table-cell; width: 50%; padding: 10px; background: #f9f9f9; border: 1px solid #eee; border-radius: 4px; vertical-align: top; }
        
        .stat-box { text-align: center; padding: 15px; background: #fff; border: 1px solid #ddd; border-radius: 4px; }
        .stat-value { font-size: 20px; font-weight: bold; color: #009688; }
        .stat-label { font-size: 12px; color: #666; text-transform: uppercase; margin-top: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f1f1f1; border: 1px solid #ddd; padding: 8px; text-align: left; font-weight: bold; }
        td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
        tr:nth-child(even) { background-color: #fafafa; }
        
        .code-block { background: #f4f4f4; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-family: monospace; font-size: 11px; white-space: pre-wrap; word-wrap: break-word; }
        
        .status-tag { padding: 4px 8px; border-radius: 4px; color: #fff; font-weight: bold; font-size: 11px; }
        .bg-green { background-color: #4CAF50; }
        .bg-red { background-color: #f44336; }
        .bg-blue { background-color: #2196F3; }
        
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 10px; }
        
        .timeline-table td { padding: 4px 8px; }
        .timeline-bar { height: 10px; background: #009688; border-radius: 2px; }
    </style>
</head>
<body>
    @php
        $totalRequests = (int)$testResult->total_requests;
        $successfulRequests = (int)$testResult->successful_requests;
        $successRate = $totalRequests > 0 ? ($successfulRequests / $totalRequests) * 100 : 0;
    @endphp

    <div class="header">
        <h1>Test Result Report #{{ $testResult->id }}</h1>
        <p>{{ $testResult->protocol }}://{{ $testResult->url }} ({{ $testResult->method }})</p>
        <p>Executed on: {{ $testResult->created_at->format('F d, Y \a\t H:i:s') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Test Overview</div>
        <div class="grid-container">
            <div class="grid-item">
                <table style="border:none;">
                    <tr style="background:none;"><td style="border:none; padding:2px; font-weight:bold; width:100px;">Status:</td><td style="border:none; padding:2px;">
                        <span class="status-tag {{ $testResult->status == 'completed' ? 'bg-green' : ($testResult->status == 'failed' ? 'bg-red' : 'bg-blue') }}">
                            {{ strtoupper($testResult->status) }}
                        </span>
                    </td></tr>
                    <tr style="background:none;"><td style="border:none; padding:2px; font-weight:bold;">Method:</td><td style="border:none; padding:2px;">{{ $testResult->method }}</td></tr>
                    <tr style="background:none;"><td style="border:none; padding:2px; font-weight:bold;">Concurrency:</td><td style="border:none; padding:2px;">{{ $testResult->concurrency_level }}</td></tr>
                    <tr style="background:none;"><td style="border:none; padding:2px; font-weight:bold;">Timeout:</td><td style="border:none; padding:2px;">{{ $testResult->timeout }}s</td></tr>
                </table>
            </div>
            <div class="grid-item">
                <div style="display: table; width: 100%;">
                    <div style="display: table-cell; width: 33%; padding: 5px;">
                        <div class="stat-box">
                            <div class="stat-value">{{ number_format($successRate, 2) }}%</div>
                            <div class="stat-label">Success</div>
                        </div>
                    </div>
                    <div style="display: table-cell; width: 33%; padding: 5px;">
                        <div class="stat-box">
                            <div class="stat-value">{{ number_format($testResult->average_response_time, 3) }}s</div>
                            <div class="stat-label">Avg Time</div>
                        </div>
                    </div>
                    <div style="display: table-cell; width: 33%; padding: 5px;">
                        <div class="stat-box">
                            <div class="stat-value">{{ $totalRequests }}</div>
                            <div class="stat-label">Requests</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($testResult->request_headers)
    <div class="section">
        <div class="section-title">Request Headers</div>
        <div class="code-block">{{ json_encode($testResult->request_headers, JSON_PRETTY_PRINT) }}</div>
    </div>
    @endif

    @if($testResult->request_body)
    <div class="section">
        <div class="section-title">Request Body</div>
        <div class="code-block">{{ is_array($testResult->request_body) ? json_encode($testResult->request_body, JSON_PRETTY_PRINT) : $testResult->request_body }}</div>
    </div>
    @endif

    @if($testResult->response_times && count($testResult->response_times) > 0)
    <div class="section">
        <div class="section-title">Response Timeline Metrics</div>
        <table class="timeline-table">
            <thead>
                <tr>
                    <th width="10%">#</th>
                    <th width="20%">Status</th>
                    <th width="20%">Time (s)</th>
                    <th width="50%">Performance Visual</th>
                </tr>
            </thead>
            <tbody>
                @php $maxTime = max(array_column($testResult->response_times, 'time')) ?: 1; @endphp
                @foreach(array_slice($testResult->response_times, 0, 50) as $index => $resp)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <span style="color: {{ $resp['status'] >= 200 && $resp['status'] < 300 ? '#4CAF50' : '#f44336' }}; font-weight: bold;">
                            {{ $resp['status'] }}
                        </span>
                    </td>
                    <td>{{ number_format($resp['time'], 4) }}s</td>
                    <td>
                        <div style="width: 100%; background: #eee; height: 10px; border-radius: 2px;">
                            <div class="timeline-bar" style="width: {{ ($resp['time'] / $maxTime) * 100 }}%;"></div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if(count($testResult->response_times) > 50)
            <p style="text-align: center; color: #999; font-style: italic;">... and {{ count($testResult->response_times) - 50 }} more requests truncated in PDF view ...</p>
        @endif
    </div>
    @endif

    @if($testResult->error_details && count($testResult->error_details) > 0)
    <div class="section">
        <div class="section-title" style="color: #f44336; border-color: #f44336; background: #fef2f2;">Errors Found</div>
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                @foreach($testResult->error_details as $error)
                <tr>
                    <td style="color:#f44336; font-weight:bold;">{{ $error['type'] ?? 'Unknown' }}</td>
                    <td>{{ $error['message'] ?? 'No message provided' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        Generated by Pest Dashboard - &copy; {{ date('Y') }}
    </div>
</body>
</html>
