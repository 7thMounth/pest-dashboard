@extends('layouts.w3app')

@section('content')
<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m12">
            <div class="w3-card-4">
                <header class="w3-container w3-light-grey" style="padding: 16px 24px;">
                    <div class="w3-row w3-margin-0">
                        <div class="w3-col m6 w3-padding-small">
                            <h1 class="w3-xlarge w3-margin-0">Test Results</h1>
                        </div>
                        <div class="w3-col m6 w3-padding-small">
                            <div class="w3-right" style="display: flex; gap: 8px; align-items: center;">
                                <form id="exportForm" action="{{ route('test-results.export') }}" method="POST" class="w3-inline" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                    <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                    <button type="submit" class="w3-button w3-green">
                                        <i class="fa fa-file-excel-o"></i> Export to Excel
                                    </button>
                                </form>
                                <button onclick="document.getElementById('newTestModal').style.display='block'" class="w3-button w3-blue" style="margin: 0;">
                                    <i class="fa fa-plus-circle"></i> New Test
                                </button>
                            </div>
                        </div>
                    </div>
                </header>
                <div class="w3-container w3-padding-16">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('test-results.index') }}" class="w3-row w3-margin-0">
                        <div class="w3-col m3 w3-padding-small">
                            <label for="start_date" class="w3-text-dark-grey w3-small">Start Date</label>
                            <input type="date" class="w3-input w3-border w3-round" id="start_date" name="start_date" value="{{ $startDate ?? '' }}">
                        </div>
                        <div class="w3-col m3 w3-padding-small">
                            <label for="end_date" class="w3-text-dark-grey w3-small">End Date</label>
                            <input type="date" class="w3-input w3-border w3-round" id="end_date" name="end_date" value="{{ $endDate ?? '' }}">
                        </div>
                        <div class="w3-col m2 w3-padding-small">
                            <div style="height: 20px;"></div> <!-- Spacer for alignment -->
                            <div class="w3-row">
                                <div class="w3-col m6 w3-padding-small">
                                    <button type="submit" class="w3-button w3-blue w3-round w3-block" title="Apply filters">
                                        <i class="fa fa-filter"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="w3-container">
                    <div class="w3-responsive">
                        <table class="w3-table-all w3-hoverable">
                            <thead>
                                <tr>
                                    <th>@sortablelink('id', 'ID')</th>
                                    <th>@sortablelink('url', 'URL')</th>
                                    <th>@sortablelink('method', 'Method')</th>
                                    <th>@sortablelink('concurrency_level', 'Concurrency')</th>
                                    <th>Success Rate</th>
                                    <th>@sortablelink('average_response_time', 'Avg. Response')</th>
                                    <th>@sortablelink('created_at', 'Created At')</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($testResults as $result)
                                <tr>
                                    <td>{{ $result->id }}</td>
                                    <td class="w3-text-truncate" style="max-width: 200px;" title="{{ $result->url }}">
                                        {{ $result->url }}
                                    </td>
                                    <td><span class="w3-tag w3-blue">{{ $result->method }}</span></td>
                                    <td>{{ number_format($result->concurrency_level) }}</td>
                                    <td>
                                        @php
                                            $successRate = $result->success_rate;
                                        @endphp
                                        <div class="w3-light-grey w3-round-large" style="height: 20px; position: relative;">
                                            @if($successRate > 0)
                                            <div class="w3-container w3-green w3-round-large" style="width: {{ $successRate }}%; position: absolute; top: 0; bottom: 0;">
                                            </div>
                                            @endif
                                            <div class="w3-display-container w3-center {{ $successRate < 50 ? 'w3-text-black' : 'w3-text-white' }}" style="position: relative; line-height: 20px; text-shadow: {{ $successRate < 50 ? 'none' : '0 0 2px #000' }};">
                                                {{ number_format($successRate, 2) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ is_numeric($result->average_response_time) ? number_format($result->average_response_time, 2) . 's' : 'N/A' }}</td>
                                    <td>{{ $result->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td class="w3-nowrap">
                                        <div class="w3-bar">
                                            <a href="{{ route('test-results.show', $result) }}" class="w3-button w3-small w3-blue" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <form action="{{ route('test-results.destroy', $result) }}" method="POST" class="w3-display-inline-block" onsubmit="return confirm('Are you sure you want to delete this test result? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w3-button w3-small w3-red" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="w3-center">No test results found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="w3-container w3-margin-top w3-padding-16">
                        <form method="GET" action="{{ route('test-results.index') }}" class="w3-row-padding">
                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                            <div class="w3-col m2">
                                <select class="w3-select w3-border w3-round w3-small" id="per_page" name="per_page" onchange="this.form.submit()" style="padding: 4px 8px; height: 36px;">
                                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 per page</option>
                                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 per page</option>
                                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 per page</option>
                                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 per page</option>
                                </select>
                            </div>
                        </form>
                            <div class="w3-col m10 w3-center">
                                <div class="w3-bar w3-border w3-round">
                                    {{-- Previous Page Link --}}
                                    @if ($testResults->onFirstPage())
                                        <span class="w3-button w3-disabled">&laquo;</span>
                                    @else
                                        <a href="{{ $testResults->previousPageUrl() }}" class="w3-button w3-hover-theme" rel="prev">&laquo;</a>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($testResults->getUrlRange(1, $testResults->lastPage()) as $page => $url)
                                        @if ($page == $testResults->currentPage())
                                            <span class="w3-button w3-theme">{{ $page }}</span>
                                        @elseif (($page >= $testResults->currentPage() - 2 && $page <= $testResults->currentPage() + 2) || 
                                                $page == 1 || 
                                                $page == $testResults->lastPage())
                                            <a href="{{ $url }}" class="w3-button w3-hover-theme">{{ $page }}</a>
                                        @elseif (($page == $testResults->currentPage() - 3 || $page == $testResults->currentPage() + 3) && 
                                                $page != 1 && $page != $testResults->lastPage())
                                            <span class="w3-button">...</span>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($testResults->hasMorePages())
                                        <a href="{{ $testResults->nextPageUrl() }}" class="w3-button w3-hover-theme" rel="next">&raquo;</a>
                                    @else
                                        <span class="w3-button w3-disabled">&raquo;</span>
                                    @endif
                                </div> <!-- Close w3-bar -->
                            </div> <!-- Close w3-col m10 -->
                        </div> <!-- Close w3-row-padding -->
                        <div class="w3-small w3-margin-top w3-center">
                            Showing {{ $testResults->firstItem() }} to {{ $testResults->lastItem() }} of {{ $testResults->total() }} results
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Test Modal -->
<div id="newTestModal" class="w3-modal" style="display: none; align-items: center;">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="width: 90%; max-width: 900px; max-height: 90vh; overflow-y: auto;">
        <div class="w3-container w3-padding-24">
            <div class="w3-right">
                <span onclick="document.getElementById('newTestModal').style.display='none'" 
                class="w3-button w3-transparent w3-hover-grey w3-xlarge w3-display-topright">&times;</span>
            </div>
            <h3 class="w3-large w3-padding-16 w3-border-bottom">Create New Test</h3>
            <form method="POST" action="{{ route('test-results.store') }}" class="w3-container">
                @csrf
                
                <!-- Main Form Section -->
                <div class="w3-section">
                    <!-- URL and Protocol -->
                    <div class="w3-row-padding">
                        <div class="w3-col m2 w3-padding-small">
                            <label for="protocol" class="w3-text-dark-grey">Protocol</label>
                            <select id="protocol" class="w3-select w3-border @error('protocol') w3-border-red @enderror" name="protocol" required>
                                <option value="http" {{ (old('protocol', 'https') === 'http' ? 'selected' : '') }}>HTTP</option>
                                <option value="https" {{ (old('protocol', 'https') === 'https' ? 'selected' : '') }}>HTTPS</option>
                            </select>
                            @error('protocol')
                                <div class="w3-text-red w3-small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="w3-col m10 w3-padding-small">
                            <label for="url" class="w3-text-dark-grey">URL</label>
                            <input id="url" type="text" class="w3-input w3-border @error('url') w3-border-red @enderror" 
                                   name="url" value="{{ old('url') }}" required>
                            <div class="w3-small w3-text-grey">Enter the URL without protocol (e.g., example.com/api)</div>
                            @error('url')
                                <div class="w3-text-red w3-small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Concurrency Level -->
                    <div class="w3-row-padding">
                        <div class="w3-col m2 w3-padding-small">
                            <label for="method" class="w3-text-dark-grey">Method</label>
                            <select id="method" class="w3-select w3-border @error('method') w3-border-red @enderror" name="method" required>
                                <option value="GET" {{ (old('method') === 'GET' ? 'selected' : '') }}>GET</option>
                                <option value="POST" {{ (old('method') === 'POST' ? 'selected' : '') }}>POST</option>
                                <option value="PUT" {{ (old('method') === 'PUT' ? 'selected' : '') }}>PUT</option>
                                <option value="DELETE" {{ (old('method') === 'DELETE' ? 'selected' : '') }}>DELETE</option>
                                <option value="PATCH" {{ (old('method') === 'PATCH' ? 'selected' : '') }}>PATCH</option>
                            </select>
                            @error('method')
                                <div class="w3-text-red w3-small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="w3-col m4 w3-padding-small">
                            <label for="concurrency_level" class="w3-text-dark-grey">Concurrency Level</label>
                            <input id="concurrency_level" type="number" class="w3-input w3-border @error('concurrency_level') w3-border-red @enderror" 
                                   name="concurrency_level" value="{{ old('concurrency_level', 1) }}" min="1" required>
                            @error('concurrency_level')
                                <div class="w3-text-red w3-small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Headers Section -->
                    <div class="w3-panel w3-light-grey w3-padding-16 w3-round">
                        <h4 class="w3-text-dark-grey">Request Headers</h4>
                        
                        <div class="w3-row-padding">
                            <div class="w3-col m4 w3-padding-small">
                                <label for="content_type" class="w3-text-dark-grey">Content Type</label>
                                <select id="content_type" class="w3-select w3-border">
                                    <option value="application/json">application/json</option>
                                    <option value="application/x-www-form-urlencoded">application/x-www-form-urlencoded</option>
                                    <option value="multipart/form-data">multipart/form-data</option>
                                    <option value="text/plain">text/plain</option>
                                    <option value="text/html">text/html</option>
                                    <option value="application/xml">application/xml</option>
                                    <option value="">Custom</option>
                                </select>
                            </div>
                            
                            <div class="w3-col m8 w3-padding-small">
                                <label for="authorization" class="w3-text-dark-grey">Authorization (Bearer)</label>
                                <div class="w3-input-group">
                                    <input type="text" id="authorization" class="w3-input" placeholder="Enter token">
                                </div>
                            </div>
                        </div>
                        
                        <div class="w3-padding-small">
                            <label for="request_headers" class="w3-text-dark-grey">Additional Headers (JSON)</label>
                            <textarea id="request_headers" class="w3-input w3-border @error('request_headers') w3-border-red @enderror" 
                                     name="request_headers" rows="3" style="resize: vertical;">{{ old('request_headers', '{}') }}</textarea>
                            <div class="w3-small w3-text-grey">Example: {"X-Custom-Header": "value"}</div>
                            @error('request_headers')
                                <div class="w3-text-red w3-small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Headers Preview -->
                        <div class="w3-padding-small">
                            <label class="w3-text-dark-grey">Headers Preview</label>
                            <div class="w3-panel w3-border w3-light-grey w3-padding w3-small" style="max-height: 120px; overflow-y: auto;">
                                <pre id="headers_preview" style="margin: 0; white-space: pre-wrap; word-wrap: break-word;">{}</pre>
                            </div>
                            <input type="hidden" name="final_headers" id="final_headers" value='{{ old('final_headers', '{}') }}'>
                        </div>
                    </div>
                    
                    <!-- Request Body -->
                    <div class="w3-padding-small">
                        <label for="request_body" class="w3-text-dark-grey">Request Body (JSON)</label>
                        <textarea id="request_body" class="w3-input w3-border @error('request_body') w3-border-red @enderror" 
                                 name="request_body" rows="6" style="resize: vertical;">{{ old('request_body') }}</textarea>
                        @error('request_body')
                            <div class="w3-text-red w3-small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <!-- Form Actions -->
                <div class="w3-padding-16 w3-border-top">
                    <button type="button" 
                            onclick="document.getElementById('newTestModal').style.display='none'" 
                            class="w3-button w3-light-grey w3-margin-right">Cancel</button>
                    <button type="submit" class="w3-button w3-blue">
                        <i class="fa fa-play"></i> Start Test
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Close modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target == document.getElementById('newTestModal')) {
            document.getElementById('newTestModal').style.display = 'none';
        }
    }

    // Header management for the test form
    document.addEventListener('DOMContentLoaded', function() {
        const contentTypeSelect = document.getElementById('content_type');
        const authorizationInput = document.getElementById('authorization');
        const requestHeadersTextarea = document.getElementById('request_headers');
        const headersPreview = document.getElementById('headers_preview');
        const finalHeadersInput = document.getElementById('final_headers');
        
        function updateHeadersPreview() {
            try {
                let headers = {};
                
                // Add content type if selected
                if (contentTypeSelect.value) {
                    headers['Content-Type'] = contentTypeSelect.value;
                }
                
                // Add authorization if provided
                if (authorizationInput.value) {
                    headers['Authorization'] = `Bearer ${authorizationInput.value}`;
                }
                
                // Add custom headers if provided
                try {
                    const customHeaders = JSON.parse(requestHeadersTextarea.value || '{}');
                    headers = { ...headers, ...customHeaders };
                } catch (e) {
                    console.error('Invalid JSON in custom headers');
                }
                
                // Update preview and hidden input
                headersPreview.textContent = JSON.stringify(headers, null, 2);
                finalHeadersInput.value = JSON.stringify(headers);
            } catch (e) {
                console.error('Error updating headers preview:', e);
            }
        }
        
        // Add event listeners
        if (contentTypeSelect) contentTypeSelect.addEventListener('change', updateHeadersPreview);
        if (authorizationInput) authorizationInput.addEventListener('input', updateHeadersPreview);
        if (requestHeadersTextarea) requestHeadersTextarea.addEventListener('input', updateHeadersPreview);
        
        // Initial update
        updateHeadersPreview();
    });
    
    // Initialize date pickers
    document.addEventListener('DOMContentLoaded', function() {
        // Set max date for end date to today
        const today = new Date().toISOString().split('T')[0];
        const endDateEl = document.getElementById('end_date');
        const startDateEl = document.getElementById('start_date');
        
        if (endDateEl) {
            endDateEl.max = today;
            startDateEl.max = today;
            
            // Set min date for start date when end date is selected
            endDateEl.addEventListener('change', function() {
                startDateEl.max = this.value;
            });
        }
    });
</script>
@endpush

@endsection
