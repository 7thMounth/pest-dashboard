<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Test Result #{{ $testResult->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin-bottom: 5px; }
        .header p { margin: 0; color: #666; }
        .card { border: 1px solid #ddd; border-radius: 4px; padding: 15px; margin-bottom: 20px; }
        .card-header { background-color: #f8f9fa; padding: 10px; margin: -15px -15px 15px -15px; border-bottom: 1px solid #ddd; }
        .card-title { margin: 0; font-size: 16px; font-weight: bold; }
        .row { display: flex; flex-wrap: wrap; margin: 0 -15px; }
        .col-6 { flex: 0 0 50%; padding: 0 15px; box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; }
        .progress { height: 20px; background-color: #e9ecef; border-radius: 4px; overflow: hidden; margin-bottom: 10px; }
        .progress-bar { background-color: #28a745; height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Test Result #{{ $testResult->id }}</h1>
        <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Test Information</h2>
        </div>
        <div class="row">
            <div class="col-6">
                <p><strong>URL:</strong> {{ $testResult->url }}</p>
                <p><strong>Method:</strong> {{ $testResult->method }}</p>
                <p><strong>Concurrency Level:</strong> {{ number_format($testResult->concurrency_level) }}</p>
            </div>
            <div class="col-6">
                <p><strong>Total Requests:</strong> {{ number_format($testResult->total_requests) }}</p>
                <p><strong>Successful Requests:</strong> {{ number_format($testResult->successful_requests) }}</p>
                <p><strong>Failed Requests:</strong> {{ number_format($testResult->failed_requests) }}</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Performance Metrics</h2>
        </div>
        <div class="row">
            <div class="col-6">
                <p><strong>Success Rate</strong></p>
                <div class="progress">
                    <div class="progress-bar" style="width: {{ $testResult->success_rate }}%;">
                        {{ number_format($testResult->success_rate, 2) }}%
                    </div>
                </div>
                <p><strong>Average Response Time:</strong> {{ is_numeric($testResult->average_response_time) ? number_format($testResult->average_response_time, 2) . 's' : 'N/A' }}</p>
            </div>
            <div class="col-6">
                <p><strong>Test Started At:</strong> {{ $testResult->created_at->format('Y-m-d H:i:s') }}</p>
                <p><strong>Test Completed At:</strong> {{ $testResult->updated_at->format('Y-m-d H:i:s') }}</p>
                <p><strong>Test Duration:</strong> {{ $testResult->created_at->diffForHumans($testResult->updated_at, true) }}</p>
            </div>
        </div>
    </div>

    @if($testResult->error_details)
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Error Details</h2>
        </div>
        <pre style="white-space: pre-wrap;">{{ $testResult->error_details }}</pre>
    </div>
    @endif
</body>
</html>
