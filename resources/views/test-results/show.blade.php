@extends('layouts.w3app')

@push('styles')
<style>
    .w3-progressbar {
        height: 25px;
        margin-bottom: 1rem;
    }
    .w3-progressbar .w3-progressbar-value {
        transition: width 0.3s ease;
    }
    .test-status {
        font-size: 1.1rem;
        margin-bottom: 1rem;
        font-weight: 500;
    }
    .status-badge {
        font-size: 0.9rem;
        padding: 0.35rem 0.75rem;
        border-radius: 50rem;
    }
</style>
@endpush

@section('content')
<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m12">
            <div class="w3-card-4">
                <header class="w3-container w3-light-grey w3-padding-16">
                    <div class="w3-row">
                        <div class="w3-col m6">
                            <h3 class="w3-margin-0">Test Result Details</h3>
                        </div>
                        <div class="w3-col m6 w3-right-align">
                            <a href="{{ route('test-results.pdf', $testResult) }}" class="w3-button w3-red w3-margin-left" target="_blank" title="Export to PDF">
                                <i class="fa fa-file-pdf-o"></i> Export PDF
                            </a>
                            <a href="{{ route('test-results.index') }}" class="w3-button w3-grey w3-margin-left" title="Back to list">
                                <i class="fa fa-list"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="w3-bar w3-margin-top">
                        <a href="{{ route('test-results.create', ['url' => $testResult->url, 'protocol' => $testResult->protocol, 'method' => $testResult->method, 'concurrency_level' => $testResult->concurrency_level]) }}" class="w3-button w3-blue w3-margin-right w3-margin-bottom">
                            <i class="fa fa-repeat"></i> Retry Test
                        </a>
                        <a href="{{ route('test-results.create', [
                            'url' => $testResult->url, 
                            'protocol' => $testResult->protocol, 
                            'method' => $testResult->method, 
                            'concurrency_level' => $testResult->concurrency_level, 
                            'test_result_id' => $testResult->id,
                            'clone' => true
                        ]) }}" class="w3-button w3-border w3-margin-right w3-margin-bottom" title="Create a new test with these parameters without running it">
                            <i class="fa fa-files-o"></i> Clone
                        </a>
                        <form action="{{ route('test-results.destroy', $testResult) }}" method="POST" class="w3-display-inline-block w3-margin-right w3-margin-bottom" onsubmit="return confirm('Are you sure you want to delete this test result? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w3-button w3-red">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </header>
                <div class="w3-container">
                    @if(in_array($testResult->status, ['queued', 'running']))
                        <div class="w3-panel w3-pale-blue w3-border w3-border-blue w3-text-blue">
                            Test Status: 
                            <span class="w3-tag w3-blue w3-round">{{ ucfirst($testResult->status) }}</span>
                        </div>
                        <div class="w3-light-grey w3-round-large">
                            <div class="w3-container w3-blue w3-round-large w3-center" style="width: {{ $testResult->progress }}%;">
                                {{ $testResult->progress }}%
                            </div>
                        </div>
                    @endif
                    <div class="w3-row w3-margin-top">
                        <div class="w3-col m6">
                            <h4>Test Configuration</h4>
                            <p><strong>URL:</strong> {{ $testResult->protocol }}://{{ $testResult->url }}</p>
                            <p><strong>Method:</strong> {{ $testResult->method }}</p>
                            <p><strong>Concurrency Level:</strong> {{ $testResult->concurrency_level }}</p>
                        </div>
                        <div class="w3-col m6">
                            <h4>Test Statistics</h4>
                            @php
                                $totalRequests = (int)$testResult->total_requests;
                                $successfulRequests = (int)$testResult->successful_requests;
                                $successRate = $totalRequests > 0 
                                    ? number_format(($successfulRequests / $totalRequests) * 100, 2) 
                                    : 0;
                                $avgResponseTime = is_numeric($testResult->average_response_time) 
                                    ? number_format($testResult->average_response_time, 2) 
                                    : '0.00';
                            @endphp
                            <p><strong>Total Requests:</strong> {{ $totalRequests }}</p>
                            <p><strong>Successful Requests:</strong> 
                                <span class="successful-requests">{{ $successfulRequests }}</span> 
                                ({{ $successRate }}%)
                            </p>
                            <p><strong>Failed Requests:</strong> <span class="failed-requests">{{ $testResult->failed_requests }}</span></p>
                            <p><strong>Average Response Time:</strong> <span class="avg-response-time">{{ $avgResponseTime }}s</span></p>
                        </div>
                    </div>
                    @if($testResult->request_headers)
                    @php
                        $token = $testResult->request_headers['Authorization'] ?? null;
                        $token = is_array($token) ? ($token[0] ?? null) : $token;
                        $displayToken = $token ? substr($token, 0, 15) . '...' : 'No token found';
                    @endphp
                    <div class="w3-card w3-margin-top">
                        <header class="w3-container w3-light-grey w3-padding-16">
                            <div class="w3-row">
                                <div class="w3-col m6">
                                    <h4 class="w3-margin-0">Request Headers</h4>
                                </div>
                                @if($token)
                                <div class="w3-col m6 w3-right-align">
                                    <div class="w3-input-group w3-small" style="display: inline-flex; max-width: 300px;">
                                        <input type="text" class="w3-input" id="authToken" value="{{ $displayToken }}" readonly>
                                        <button class="w3-button w3-blue" type="button" id="copyTokenBtn" data-token="{{ $token }}" title="Copy full token">
                                            <i class="fa fa-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </header>
                        <div class="w3-container w3-padding">
                            <div class="w3-panel w3-light-grey w3-padding-small" style="overflow-x: auto;">
                                <pre class="w3-small" style="white-space: pre-wrap; word-wrap: break-word; margin: 0;">{{ json_encode($testResult->request_headers, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($testResult->request_body)
                    <div class="w3-card w3-margin-top">
                        <header class="w3-container w3-light-grey">Request Body</header>
                        <div class="w3-container w3-padding">
                            <div class="w3-panel w3-light-grey w3-padding-small" style="overflow-x: auto;">
                                <pre class="w3-small" style="white-space: pre-wrap; word-wrap: break-word; margin: 0;">{{ json_encode($testResult->request_body, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                    @endif
                    @php
                        $hasErrors = is_array($testResult->error_details) && count($testResult->error_details) > 0;
                        $errorCount = $hasErrors ? count($testResult->error_details) : 0;
                    @endphp
                    @if($hasErrors)
                    <div class="w3-card w3-margin-top">
                        <header class="w3-container w3-light-grey">Errors ({{ $errorCount }} total)</header>
                        <div class="w3-container w3-padding-0">
                            <div class="w3-ul w3-small">
                                @php
                                    $groupedErrors = [];
                                    foreach ($testResult->error_details as $error) {
                                        $message = $error['error'];
                                        if (!isset($groupedErrors[$message])) {
                                            $groupedErrors[$message] = [
                                                'count' => 0,
                                                'requests' => []
                                            ];
                                        }
                                        $groupedErrors[$message]['count']++;
                                        $groupedErrors[$message]['requests'][] = $error['request'];
                                    }
                                @endphp
                                @foreach($groupedErrors as $error => $details)
                                <li class="w3-bar">
                                    <div class="w3-bar-item">
                                        <div class="w3-large w3-text-red">{{ $error }}</div>
                                        <small class="w3-text-grey">
                                            @php
                                                $requests = $details['requests'];
                                                $requestText = count($requests) > 5 
                                                    ? 'Requests ' . min($requests) . ' to ' . max($requests) . ' (' . count($requests) . ' total)'
                                                    : 'Request' . (count($requests) > 1 ? 's' : '') . ' ' . implode(', ', $requests);
                                            @endphp
                                            {{ $requestText }}
                                        </small>
                                    </div>
                                    <div class="w3-bar-item w3-right">
                                        <span class="w3-tag w3-red w3-round">{{ $details['count'] }}</span>
                                    </div>
                                </li>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="w3-card w3-margin-top">
                        <header class="w3-container w3-light-grey">Response Times</header>
                        <div class="w3-container w3-padding-0">
                            <table class="w3-table w3-striped w3-hoverable w3-small">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Status</th>
                                        <th>Response Time (s)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($paginatedResponseTimes->count() > 0)
                                        @foreach($paginatedResponseTimes as $index => $item)
                                            @php
                                                $isStruct = is_array($item);
                                                $time = $isStruct ? $item['time'] : $item;
                                                $status = $isStruct ? $item['status'] : null;
                                                $isSuccess = in_array($status, [200, 201]);
                                            @endphp
                                        <tr>
                                            <td>{{ ($paginatedResponseTimes->currentPage() - 1) * $paginatedResponseTimes->perPage() + $loop->iteration }}</td>
                                            <td>
                                                @if($status)
                                                    <span class="w3-tag {{ $isSuccess ? 'w3-green' : 'w3-red' }} w3-round-small">{{ $status }}</span>
                                                @else
                                                    <span class="w3-text-grey">-</span>
                                                @endif
                                            </td>
                                            <td class="w3-monospace">{{ is_numeric($time) ? number_format($time, 4) : 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2" class="w3-center w3-padding-16">No response times available</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            @if($paginatedResponseTimes->hasPages())
                            <div class="w3-container w3-margin-top w3-padding-16">
                                <div class="w3-row-padding">
                                    <div class="w3-col m2">
                                        <select class="w3-select w3-border w3-round w3-small" id="per_page" name="per_page" onchange="window.location.href = updateQueryStringParameter(window.location.href, 'per_page', this.value)" style="padding: 4px 8px; height: 36px;">
                                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per page</option>
                                            <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25 per page</option>
                                            <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 per page</option>
                                            <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100 per page</option>
                                        </select>
                                    </div>
                                    <div class="w3-col m10 w3-center">
                                        <div class="w3-bar w3-border w3-round">
                                            {{-- Previous Page Link --}}
                                            @if ($paginatedResponseTimes->onFirstPage())
                                                <span class="w3-button w3-disabled">&laquo;</span>
                                            @else
                                                <a href="{{ $paginatedResponseTimes->previousPageUrl() }}" class="w3-button w3-hover-theme" rel="prev">&laquo;</a>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($paginatedResponseTimes->getUrlRange(1, $paginatedResponseTimes->lastPage()) as $page => $url)
                                                @if ($page == $paginatedResponseTimes->currentPage())
                                                    <span class="w3-button w3-theme">{{ $page }}</span>
                                                @elseif (($page >= $paginatedResponseTimes->currentPage() - 2 && $page <= $paginatedResponseTimes->currentPage() + 2) || 
                                                        $page == 1 || 
                                                        $page == $paginatedResponseTimes->lastPage())
                                                    <a href="{{ $url }}" class="w3-button w3-hover-theme">{{ $page }}</a>
                                                @elseif (($page == $paginatedResponseTimes->currentPage() - 3 || $page == $paginatedResponseTimes->currentPage() + 3) && 
                                                        $page != 1 && $page != $paginatedResponseTimes->lastPage())
                                                    <span class="w3-button">...</span>
                                                @endif
                                            @endforeach

                                            {{-- Next Page Link --}}
                                            @if ($paginatedResponseTimes->hasMorePages())
                                                <a href="{{ $paginatedResponseTimes->nextPageUrl() }}" class="w3-button w3-hover-theme" rel="next">&raquo;</a>
                                            @else
                                                <span class="w3-button w3-disabled">&raquo;</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="w3-small w3-margin-top w3-center">
                                    Showing {{ $paginatedResponseTimes->firstItem() }} to {{ $paginatedResponseTimes->lastItem() }} of {{ $paginatedResponseTimes->total() }} results
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@push('scripts')

<script>
    function updateQueryStringParameter(uri, key, value) {
        const re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        const separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        } else {
            return uri + separator + key + "=" + value;
        }
    }
    // Copy token to clipboard
    document.getElementById('copyTokenBtn')?.addEventListener('click', function() {
        const token = this.getAttribute('data-token');
        const tempInput = document.createElement('input');
        document.body.appendChild(tempInput);
        tempInput.value = token.replace(/^Bearer\s+/i, '');
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        // Update button text temporarily
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fa fa-check"></i> Copied!';
        this.classList.remove('w3-button');
        this.classList.add('w3-button', 'w3-green');
        
        // Reset button after 2 seconds
        setTimeout(() => {
            this.innerHTML = originalText;
            this.classList.remove('w3-green');
            this.classList.add('w3-button', 'w3-blue');
        }, 2000);
    });
</script>

@if(in_array($testResult->status, ['queued', 'running']))
<script>
    function checkStatus() {
        fetch('{{ route('test-results.status', $testResult) }}')
            .then(response => response.json())
            .then(data => {
                // Update progress bar
                const progressBar = document.querySelector('.w3-progressbar-value');
                progressBar.style.width = data.progress + '%';
                progressBar.textContent = data.progress + '%';

                // Update status badge
                if (data.status !== '{{ $testResult->status }}') {
                    const statusBadge = document.querySelector('.status-badge');
                    statusBadge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                    statusBadge.className = 'w3-tag w3-' + (data.status === 'running' ? 'blue' : 'green') + ' w3-round';
                }

                // Update statistics
                if (data.successful_requests !== null) {
                    const successEl = document.querySelector('.successful-requests');
                    if (successEl) successEl.textContent = data.successful_requests;
                }
                
                if (data.failed_requests !== null) {
                    const failedEl = document.querySelector('.failed-requests');
                    if (failedEl) failedEl.textContent = data.failed_requests;
                }
                
                if (data.average_response_time !== null) {
                    const avgTimeEl = document.querySelector('.avg-response-time');
                    if (avgTimeEl) avgTimeEl.textContent = data.average_response_time.toFixed(2) + 's';
                }

                // Stop checking if test is completed
                if (data.is_completed) {
                    // Reload the page to show final results
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    // Check again in 1 second
                    setTimeout(checkStatus, 1000);
                }
            })
            .catch(error => {
                console.error('Error checking status:', error);
                // Try again in case of error
                setTimeout(checkStatus, 3000);
            });
    }

    // Start checking status
    document.addEventListener('DOMContentLoaded', checkStatus);
</script>
@endif
@endpush
