@extends('layouts.w3app')

@push('styles')
<style>
    body, h1,h2,h3,h4,h5,h6 {font-family: "Raleway", sans-serif}
    .w3-text-teal, .w3-hover-text-teal:hover {color:#009688!important}
    .w3-teal, .w3-hover-teal:hover {color:#fff!important; background-color:#009688!important}
    
    /* Animation for status polling updates */
    .stat-update {
        animation: highlight 1.5s ease;
    }
    @keyframes highlight {
        0% { background-color: rgba(0, 150, 136, 0.2); }
        100% { background-color: transparent; }
    }
</style>
@endpush

@section('content')
@php
    $totalRequests = (int)$testResult->total_requests;
    $successfulRequests = (int)$testResult->successful_requests;
    $successRate = $totalRequests > 0 
        ? number_format(($successfulRequests / $totalRequests) * 100, 2) 
        : 0;
    $avgResponseTime = is_numeric($testResult->average_response_time) 
        ? number_format($testResult->average_response_time, 3) 
        : '0.000';
    $isProcessing = in_array($testResult->status, ['queued', 'running']);
@endphp

<!-- Page Container -->
<div class="w3-content w3-margin-top" style="max-width:1400px;">

  <!-- The Grid -->
  <div class="w3-row-padding">
  
    <!-- Left Column -->
    <div class="w3-third">
    
      <div class="w3-white w3-text-grey w3-card-4">
        <div class="w3-display-container">
          <div class="w3-container w3-teal w3-padding-32 w3-center">
            <i class="fa fa-flask w3-xxxlarge"></i>
            <h2 class="w3-margin-top">Test Result #{{ $testResult->id }}</h2>
          </div>
        </div>
        <div class="w3-container">
          <p><i class="fa fa-globe fa-fw w3-margin-right w3-large w3-text-teal"></i>{{ $testResult->protocol }}://{{ $testResult->url }}</p>
          <p><i class="fa fa-code fa-fw w3-margin-right w3-large w3-text-teal"></i>{{ $testResult->method }}</p>
          <p><i class="fa fa-calendar fa-fw w3-margin-right w3-large w3-text-teal"></i>{{ $testResult->created_at->format('M d, Y H:i') }}</p>
          <p><i class="fa fa-users fa-fw w3-margin-right w3-large w3-text-teal"></i>Concurrency: {{ $testResult->concurrency_level }}</p>
          <hr>

          <p class="w3-large"><b><i class="fa fa-asterisk fa-fw w3-margin-right w3-text-teal"></i>Success Rate</b></p>
          <p>Successful Requests</p>
          <div class="w3-light-grey w3-round-xlarge w3-small">
            <div class="w3-container w3-center w3-round-xlarge w3-teal success-rate-bar" style="width:{{ $successRate }}%">{{ $successRate }}%</div>
          </div>
          
          <p>Accuracy (200/201 Only)</p>
          <div class="w3-light-grey w3-round-xlarge w3-small">
            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:{{ $successRate }}%">{{ $successRate }}%</div>
          </div>
          <br>

          <p class="w3-large w3-text-theme"><b><i class="fa fa-info-circle fa-fw w3-margin-right w3-text-teal"></i>Status</b></p>
          <div class="w3-padding">
            <span class="status-badge w3-tag w3-round {{ $testResult->status == 'completed' ? 'w3-green' : ($testResult->status == 'failed' ? 'w3-red' : 'w3-blue') }}">
                {{ ucfirst($testResult->status) }}
            </span>
            @if($isProcessing)
                <div class="w3-light-grey w3-round-xlarge w3-small w3-margin-top">
                    <div class="w3-container w3-center w3-round-xlarge w3-blue progress-bar" style="width:{{ $testResult->progress }}%">{{ $testResult->progress }}%</div>
                </div>
            @endif
          </div>
          <br>
        </div>
      </div><br>

    <!-- End Left Column -->
    </div>

    <!-- Right Column -->
    <div class="w3-twothird">
    
      <div class="w3-container w3-card w3-white w3-margin-bottom">
        <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-bar-chart fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>Test Statistics</h2>
        <div class="w3-container">
          <div class="w3-row w3-center w3-margin-bottom">
            <div class="w3-quarter">
              <h5 class="w3-text-teal">Successful</h5>
              <p class="successful-requests">{{ $successfulRequests }}</p>
            </div>
            <div class="w3-quarter">
              <h5 class="w3-text-teal">Failed</h5>
              <p class="failed-requests">{{ $testResult->failed_requests }}</p>
            </div>
            <div class="w3-quarter">
              <h5 class="w3-text-teal">Avg Time</h5>
              <p class="avg-response-time">{{ $avgResponseTime }}s</p>
            </div>
            <div class="w3-quarter">
              <h5 class="w3-text-teal">Total</h5>
              <p>{{ $totalRequests }}</p>
            </div>
          </div>
          <hr>
          
          <div class="w3-bar w3-margin-bottom">
            @if($previousId)
                <a href="{{ route('test-results.show', $previousId) }}" class="w3-bar-item w3-button w3-light-grey w3-round w3-margin-right" title="Previous Test"><i class="fa fa-chevron-left"></i> Previous</a>
            @else
                <button class="w3-bar-item w3-button w3-light-grey w3-round w3-margin-right w3-disabled" disabled><i class="fa fa-chevron-left"></i> Previous</button>
            @endif

            @if($nextId)
                <a href="{{ route('test-results.show', $nextId) }}" class="w3-bar-item w3-button w3-light-grey w3-round w3-margin-right" title="Next Test">Next <i class="fa fa-chevron-right"></i></a>
            @else
                <button class="w3-bar-item w3-button w3-light-grey w3-round w3-margin-right w3-disabled" disabled>Next <i class="fa fa-chevron-right"></i></button>
            @endif

            <a href="{{ route('test-results.pdf', $testResult) }}" target="_blank" class="w3-bar-item w3-button w3-teal w3-round w3-margin-right"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
            <a href="{{ route('test-results.create', ['test_result_id' => $testResult->id]) }}" class="w3-bar-item w3-button w3-blue w3-round w3-margin-right"><i class="fa fa-repeat"></i> Retry</a>
            <form action="{{ route('test-results.destroy', $testResult) }}" method="POST" class="w3-right" onsubmit="return confirm('Delete this result?');">
                @csrf @method('DELETE')
                <button type="submit" class="w3-button w3-red w3-round"><i class="fa fa-trash"></i> Delete</button>
            </form>
          </div>
        </div>
      </div>

      <div class="w3-container w3-card w3-white w3-margin-bottom">
        <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-cogs fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>Configuration Details</h2>
        <div class="w3-container">
          <h5 class="w3-opacity"><b>Request Headers</b></h5>
          <div class="w3-panel w3-light-grey w3-leftbar w3-border-teal">
            @if($testResult->request_headers)
                @php
                    $token = $testResult->request_headers['Authorization'] ?? null;
                    if (is_array($token)) $token = $token[0] ?? null;
                @endphp
                <div class="w3-row w3-padding-small">
                    <div class="w3-col m10">
                        <pre class="w3-small" style="white-space: pre-wrap; word-wrap: break-word; margin: 0;">{{ json_encode($testResult->request_headers, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                    @if($token)
                    <div class="w3-col m2 w3-right-align">
                        <button class="w3-button w3-teal w3-tiny w3-round" id="copyTokenBtn" data-token="{{ $token }}" title="Copy Auth Token">
                            <i class="fa fa-copy"></i>
                        </button>
                    </div>
                    @endif
                </div>
            @else
                <p>No headers defined.</p>
            @endif
          </div>
          
          <h5 class="w3-opacity"><b>Request Body</b></h5>
          <div class="w3-panel w3-light-grey w3-leftbar w3-border-teal">
            @if($testResult->request_body)
                <pre class="w3-small" style="white-space: pre-wrap; word-wrap: break-word; margin: 0;">{{ json_encode($testResult->request_body, JSON_PRETTY_PRINT) }}</pre>
            @else
                <p>No request body defined.</p>
            @endif
          </div>
        </div>
      </div>

      @if(is_array($testResult->error_details) && count($testResult->error_details) > 0)
      <div class="w3-container w3-card w3-white w3-margin-bottom">
        <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-warning fa-fw w3-margin-right w3-xxlarge w3-text-red"></i>Errors Found</h2>
        <div class="w3-container">
            @php
                $groupedErrors = [];
                foreach ($testResult->error_details as $error) {
                    $message = $error['error'] ?? 'Unknown error';
                    if (!isset($groupedErrors[$message])) $groupedErrors[$message] = 0;
                    $groupedErrors[$message]++;
                }
            @endphp
            @foreach($groupedErrors as $message => $count)
                <div class="w3-panel w3-pale-red w3-leftbar w3-border-red">
                    <p><strong>{{ $message }}</strong> <span class="w3-tag w3-red w3-round w3-right">{{ $count }}</span></p>
                </div>
            @endforeach
        </div>
      </div>
      @endif

      <div class="w3-container w3-card w3-white">
        <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-history fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>Response Timeline</h2>
        <div class="w3-container">
          <table class="w3-table w3-striped w3-white w3-margin-bottom">
            <thead>
                <tr class="w3-teal">
                    <th>#</th>
                    <th>Status</th>
                    <th>Response Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paginatedResponseTimes as $item)
                    @php
                        $isStruct = is_array($item);
                        $time = $isStruct ? $item['time'] : $item;
                        $status = $isStruct ? $item['status'] : null;
                        $isSuccess = in_array($status, [200, 201]);
                    @endphp
                    <tr>
                        <td>{{ ($paginatedResponseTimes->currentPage() - 1) * $paginatedResponseTimes->perPage() + $loop->iteration }}</td>
                        <td>
                            <span class="w3-tag {{ $isSuccess ? 'w3-green' : 'w3-red' }} w3-round-small">
                                {{ $status ?? 'ERR' }}
                            </span>
                        </td>
                        <td class="w3-monospace">{{ is_numeric($time) ? number_format($time, 4) : '0.0000' }}s</td>
                    </tr>
                @endforeach
            </tbody>
          </table>
          
          @if($paginatedResponseTimes->hasPages())
            <div class="w3-center w3-padding-16">
                <div class="w3-bar w3-border w3-round">
                    {{ $paginatedResponseTimes->links('pagination::simple-default') }}
                </div>
            </div>
          @endif
        </div>
      </div>

    <!-- End Right Column -->
    </div>
    
  <!-- End Grid -->
  </div>
  
  <!-- End Page Container -->
</div>

@endsection

@push('scripts')
<script>
    // Copy token to clipboard
    document.getElementById('copyTokenBtn')?.addEventListener('click', function() {
        const token = this.getAttribute('data-token');
        const tempInput = document.createElement('input');
        document.body.appendChild(tempInput);
        tempInput.value = token.replace(/^Bearer\s+/i, '');
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        const originalIcon = this.innerHTML;
        this.innerHTML = '<i class="fa fa-check"></i>';
        this.classList.add('w3-green');
        setTimeout(() => {
            this.innerHTML = originalIcon;
            this.classList.remove('w3-green');
        }, 2000);
    });

    @if($isProcessing)
    function checkStatus() {
        fetch('{{ route('test-results.status', $testResult) }}')
            .then(response => response.json())
            .then(data => {
                // Update progress bar
                const progressBar = document.querySelector('.progress-bar');
                if (progressBar) {
                    progressBar.style.width = data.progress + '%';
                    progressBar.textContent = data.progress + '%';
                }

                // Update statistics with highlight animation
                updateStat('.successful-requests', data.successful_requests);
                updateStat('.failed-requests', data.failed_requests);
                updateStat('.avg-response-time', data.average_response_time.toFixed(3) + 's');
                
                // Update success rate bars
                const rate = ((data.successful_requests / {{ $testResult->concurrency_level }}) * 100).toFixed(2);
                document.querySelectorAll('.success-rate-bar').forEach(bar => {
                    bar.style.width = rate + '%';
                    bar.textContent = rate + '%';
                });

                if (data.is_completed) {
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    setTimeout(checkStatus, 1500);
                }
            });
    }

    function updateStat(selector, newValue) {
        const el = document.querySelector(selector);
        if (el && el.textContent != newValue) {
            el.textContent = newValue;
            el.classList.add('stat-update');
            setTimeout(() => el.classList.remove('stat-update'), 1500);
        }
    }

    document.addEventListener('DOMContentLoaded', checkStatus);
    @endif
</script>
@endpush

