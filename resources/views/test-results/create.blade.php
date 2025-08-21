@extends('layouts.w3app')

@section('content')
<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m12">
            <div class="w3-card-4 w3-margin-top">
                <div class="w3-container w3-padding-16">
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
                                        <option value="http" {{ (old('protocol', $old['protocol'] ?? 'https') === 'http' ? 'selected' : '') }}>HTTP</option>
                                        <option value="https" {{ (old('protocol', $old['protocol'] ?? 'https') === 'https' ? 'selected' : '') }}>HTTPS</option>
                                    </select>
                                    @error('protocol')
                                        <div class="w3-text-red w3-small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="w3-col m8 w3-padding-small">
                                    <label for="url" class="w3-text-dark-grey">URL</label>
                                    <input id="url" type="text" class="w3-input w3-border @error('url') w3-border-red @enderror" 
                                           name="url" value="{{ old('url', $old['url'] ?? '') }}" required>
                                    <div class="w3-small w3-text-grey">Enter the URL without protocol (e.g., example.com/api)</div>
                                    @error('url')
                                        <div class="w3-text-red w3-small">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="w3-col m2 w3-padding-small">
                                    <label for="method" class="w3-text-dark-grey">Method</label>
                                    <select id="method" class="w3-select w3-border @error('method') w3-border-red @enderror" name="method" required>
                                        <option value="GET" {{ (old('method', $old['method'] ?? '') === 'GET' ? 'selected' : '') }}>GET</option>
                                        <option value="POST" {{ (old('method', $old['method'] ?? '') === 'POST' ? 'selected' : '') }}>POST</option>
                                        <option value="PUT" {{ (old('method', $old['method'] ?? '') === 'PUT' ? 'selected' : '') }}>PUT</option>
                                        <option value="DELETE" {{ (old('method', $old['method'] ?? '') === 'DELETE' ? 'selected' : '') }}>DELETE</option>
                                        <option value="PATCH" {{ (old('method', $old['method'] ?? '') === 'PATCH' ? 'selected' : '') }}>PATCH</option>
                                    </select>
                                    @error('method')
                                        <div class="w3-text-red w3-small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Concurrency Level -->
                            <div class="w3-row-padding">
                                <div class="w3-col m3 w3-padding-small">
                                    <label for="concurrency_level" class="w3-text-dark-grey">Concurrency Level</label>
                                    <input id="concurrency_level" type="number" class="w3-input w3-border @error('concurrency_level') w3-border-red @enderror" 
                                           name="concurrency_level" value="{{ old('concurrency_level', $old['concurrency_level'] ?? 1) }}" min="1" required>
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
                                        <label for="authorization" class="w3-text-dark-grey">Authorization</label>
                                        <div class="w3-input-group">
                                            <span class="w3-input-group-label">Bearer</span>
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
                                         name="request_body" rows="6" style="resize: vertical;">{{ old('request_body', $old['request_body'] ?? '') }}</textarea>
                                @error('request_body')
                                    <div class="w3-text-red w3-small">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Form Actions -->
                            <div class="w3-padding-16 w3-border-top">
                                <a href="{{ route('test-results.index') }}" class="w3-button w3-light-grey w3-margin-right">Cancel</a>
                                <button type="submit" class="w3-button w3-blue">
                                    <i class="fa fa-play"></i> Start Test
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Header management for the test form
    document.addEventListener('DOMContentLoaded', function() {
        const contentTypeSelect = document.getElementById('content_type');
        const authorizationInput = document.getElementById('authorization');
        const requestHeadersTextarea = document.getElementById('request_headers');
        const headersPreview = document.getElementById('headers_preview');
        const finalHeadersInput = document.getElementById('final_headers');
        const protocolSelect = document.getElementById('protocol');
        const urlInput = document.getElementById('url');
        
        function updateHeaders() {
            try {
                // Get values from form
                const contentType = contentTypeSelect.value;
                const authToken = authorizationInput.value;
                let additionalHeaders = {};
                
                // Parse additional headers if valid JSON
                try {
                    additionalHeaders = JSON.parse(requestHeadersTextarea.value || '{}');
                } catch (e) {
                    console.error('Invalid JSON in additional headers');
                }
                
                // Build headers object
                const headers = { ...additionalHeaders };
                
                // Add content type if selected
                if (contentType) {
                    headers['Content-Type'] = contentType;
                }
                
                // Add authorization if provided
                if (authToken) {
                    headers['Authorization'] = `Bearer ${authToken}`;
                }
                
                // Update preview
                headersPreview.textContent = Object.keys(headers).length > 0 
                    ? JSON.stringify(headers, null, 2) 
                    : 'No headers defined';
                
                // Update hidden input with final headers
                finalHeadersInput.value = JSON.stringify(headers);
                
            } catch (error) {
                console.error('Error updating headers:', error);
                headersPreview.textContent = 'Error: ' + error.message;
            }
        }
        
        // Function to update URL with protocol
        function updateUrlWithProtocol() {
            const protocol = protocolSelect.value;
            let url = urlInput.value;
            
            // Remove existing protocol if present
            url = url.replace(/^https?:\/\//, '');
            
            // Only update if URL is not empty and doesn't already have a protocol
            if (url && !urlInput.value.match(/^https?:\/\//)) {
                urlInput.value = protocol + '://' + url;
            }
        }
        
        // Set up event listeners
        protocolSelect.addEventListener('change', updateUrlWithProtocol);
        contentTypeSelect.addEventListener('change', updateHeaders);
        authorizationInput.addEventListener('input', updateHeaders);
        requestHeadersTextarea.addEventListener('input', updateHeaders);
        
        // Initialize form values
        updateHeaders();
        
        // Set initial values from old input if exists
        @if(old('final_headers'))
            try {
                const oldHeaders = JSON.parse('{!! addslashes(old('final_headers', '{}')) !!}');
                if (oldHeaders['Content-Type']) {
                    contentTypeSelect.value = oldHeaders['Content-Type'];
                }
                if (oldHeaders['Authorization']) {
                    const token = oldHeaders['Authorization'].replace('Bearer ', '');
                    authorizationInput.value = token;
                }
                updateHeaders();
            } catch (e) {
                console.error('Error parsing old headers:', e);
            }
        @endif
    });
</script>
@endpush
