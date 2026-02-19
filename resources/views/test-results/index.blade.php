@extends('layouts.w3app')

@section('content')
<div class="w3-row-padding w3-margin-bottom">
    <div class="w3-quarter">
      <div class="w3-container w3-red w3-padding-16">
        <div class="w3-left"><i class="fa fa-tasks w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>{{ $totalTests }}</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Total Tests</h4>
      </div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-blue w3-padding-16">
        <div class="w3-left"><i class="fa fa-check-circle w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>{{ number_format($successRate, 1) }}%</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Success Rate</h4>
      </div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-teal w3-padding-16">
        <div class="w3-left"><i class="fa fa-clock-o w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>{{ number_format($avgResponseTime, 2) }}s</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Avg Response</h4>
      </div>
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-orange w3-text-white w3-padding-16">
        <div class="w3-left"><i class="fa fa-exclamation-triangle w3-xxxlarge"></i></div>
        <div class="w3-right">
          <h3>{{ $failedTests }}</h3>
        </div>
        <div class="w3-clear"></div>
        <h4>Failed</h4>
      </div>
    </div>
  </div>

  <div class="w3-panel">
    <div class="w3-row-padding" style="margin:0 -16px">
      <div class="w3-col">
        <h5>Recent Tests</h5>
        
        <!-- Action Bar -->
        <div class="w3-bar w3-light-grey w3-round w3-padding-small w3-margin-bottom">
            <a href="{{ route('test-results.create') }}" class="w3-bar-item w3-button w3-blue w3-round"><i class="fa fa-plus"></i> New Test</a>
            <form id="exportForm" action="{{ route('test-results.export') }}" method="POST" class="w3-bar-item w3-right">
                @csrf
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <button type="submit" class="w3-button w3-green w3-round w3-small"><i class="fa fa-file-excel-o"></i> Export</button>
            </form>
        </div>

        <table class="w3-table w3-striped w3-white">
          <thead class="w3-light-grey">
            <tr>
              <th><i class="fa fa-hashtag"></i> ID</th>
              <th><i class="fa fa-globe"></i> URL</th>
              <th>Method</th>
              <th>Status</th>
              <th>Success</th>
              <th>Time</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($testResults as $result)
            <tr>
              <td>{{ $result->id }}</td>
              <td class="w3-text-truncate" style="max-width: 200px;" title="{{ $result->url }}">{{ $result->url }}</td>
              <td><span class="w3-tag w3-blue w3-round-small">{{ $result->method }}</span></td>
              <td>
                @if($result->status == 'completed')
                    <span class="w3-text-green"><i class="fa fa-check"></i> Done</span>
                @elseif($result->status == 'failed')
                    <span class="w3-text-red"><i class="fa fa-times"></i> Failed</span>
                @else
                    <span class="w3-text-orange"><i class="fa fa-spinner fa-spin"></i> {{ ucfirst($result->status) }}</span>
                @endif
              </td>
              <td>
                <div class="w3-grey w3-round-large" style="height: 12px; width: 100px; position:relative;">
                    <div class="w3-container w3-green w3-round-large" style="width:{{ $result->success_rate }}%; height: 12px; padding:0;"></div>
                </div>
                <div class="w3-tiny w3-text-grey">{{ number_format($result->success_rate) }}%</div>
              </td>
              <td>{{ is_numeric($result->average_response_time) ? number_format($result->average_response_time, 2) . 's' : '-' }}</td>
              <td><i class="fa fa-calendar"></i> {{ $result->created_at->format('M d, H:i') }}</td>
              <td>
                <a href="{{ route('test-results.show', $result) }}" class="w3-button w3-tiny w3-blue w3-round" title="View"><i class="fa fa-eye"></i></a>
                <form action="{{ route('test-results.destroy', $result) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="w3-button w3-tiny w3-red w3-round" title="Delete"><i class="fa fa-trash"></i></button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="8" class="w3-center">No test results found</td></tr>
            @endforelse
          </tbody>
        </table>
        
        <!-- Pagination -->
        <div class="w3-margin-top w3-center">
             {{ $testResults->links('pagination::simple-default') }}
        </div>
      </div>
    </div>
  </div>



@push('scripts')

@endpush

@endsection
