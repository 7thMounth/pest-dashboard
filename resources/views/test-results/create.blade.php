@extends('layouts.w3app')

@section('content')
<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m12">
            <div class="w3-card-4 w3-margin-top">
                <div class="w3-container w3-padding-16">
                    <h3 class="w3-center w3-padding-16"><i class="fa fa-plus-circle"></i> Create New Test</h3>
                    
                    <form method="POST" action="{{ route('test-results.store') }}" class="w3-container">
                        @csrf
                        
                        <div class="w3-section">
                            <!-- URL Section -->
                            <div class="w3-row w3-section">
                                <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-globe"></i></div>
                                <div class="w3-rest">
                                    <div class="w3-row">
                                        <div class="w3-col s3">
                                            <select class="w3-select w3-border" name="protocol" id="protocol" required>
                                                <option value="http" {{ (old('protocol', $old['protocol'] ?? 'https') === 'http' ? 'selected' : '') }}>HTTP</option>
                                                <option value="https" {{ (old('protocol', $old['protocol'] ?? 'https') === 'https' ? 'selected' : '') }}>HTTPS</option>
                                            </select>
                                        </div>
                                        <div class="w3-col s9">
                                            <input id="url" class="w3-input w3-border @error('url') w3-border-red @enderror" name="url" type="text" placeholder="URL (e.g. example.com/api)" value="{{ old('url', $old['url'] ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="w3-small w3-text-grey">Enter the URL without protocol</div>
                                    @error('url') <div class="w3-text-red w3-small">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Method -->
                            <div class="w3-row w3-section">
                                <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-exchange"></i></div>
                                <div class="w3-rest">
                                    <select class="w3-select w3-border @error('method') w3-border-red @enderror" name="method" required>
                                        <option value="GET" {{ (old('method', $old['method'] ?? 'GET') === 'GET' ? 'selected' : '') }}>GET</option>
                                        <option value="POST" {{ (old('method', $old['method'] ?? '') === 'POST' ? 'selected' : '') }}>POST</option>
                                        <option value="PUT" {{ (old('method', $old['method'] ?? '') === 'PUT' ? 'selected' : '') }}>PUT</option>
                                        <option value="DELETE" {{ (old('method', $old['method'] ?? '') === 'DELETE' ? 'selected' : '') }}>DELETE</option>
                                        <option value="PATCH" {{ (old('method', $old['method'] ?? '') === 'PATCH' ? 'selected' : '') }}>PATCH</option>
                                    </select>
                                    @error('method') <div class="w3-text-red w3-small">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Concurrency & Timeout -->
                            <div class="w3-row w3-section">
                                <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-users"></i></div>
                                <div class="w3-rest">
                                    <div class="w3-row-padding" style="margin:0 -16px">
                                        <div class="w3-half">
                                            <label class="w3-text-grey w3-small">Concurrency</label>
                                            <input class="w3-input w3-border @error('concurrency_level') w3-border-red @enderror" name="concurrency_level" type="number" placeholder="Concurrency Level" value="{{ old('concurrency_level', $old['concurrency_level'] ?? 1) }}" min="1" required>
                                        </div>
                                        <div class="w3-half">
                                            <label class="w3-text-grey w3-small">Timeout (seconds)</label>
                                            <input class="w3-input w3-border @error('timeout') w3-border-red @enderror" name="timeout" type="number" placeholder="Timeout (seconds)" value="{{ old('timeout', $old['timeout'] ?? 60) }}" min="1" required>
                                        </div>
                                    </div>
                                    @error('concurrency_level') <div class="w3-text-red w3-small">{{ $message }}</div> @enderror
                                    @error('timeout') <div class="w3-text-red w3-small">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Headers Config -->
                            <div class="w3-row w3-section">
                                <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-list-ul"></i></div>
                                <div class="w3-rest">
                                     <div class="w3-card w3-light-grey w3-padding">
                                        <p class="w3-small w3-margin-0">Headers Configuration</p>
                                        <div class="w3-row">
                                            <div class="w3-col s4">
                                                <select id="content_type" class="w3-select w3-border w3-tiny">
                                                    <option value="" disabled selected>Content Type</option>
                                                    <option value="application/json">application/json</option>
                                                    <option value="application/x-www-form-urlencoded">form-urlencoded</option>
                                                    <option value="multipart/form-data">multipart/form-data</option>
                                                </select>
                                            </div>
                                            <div class="w3-col s8">
                                                <input type="text" id="authorization" class="w3-input w3-border w3-tiny" placeholder="Bearer Token">
                                            </div>
                                        </div>
                                        <textarea id="request_headers" class="w3-input w3-border w3-margin-top w3-tiny" 
                                                  name="request_headers" rows="2" placeholder='Additional JSON Headers: {"X-Key": "Value"}'>{{ old('request_headers', $old['request_headers'] ?? '{}') }}</textarea>
                                        
                                        <div class="w3-padding-small">
                                            <label class="w3-text-dark-grey w3-tiny">Preview</label>
                                            <div class="w3-panel w3-border w3-white w3-padding w3-tiny" style="max-height: 80px; overflow-y: auto;">
                                                <pre id="headers_preview" style="margin: 0; white-space: pre-wrap;">{}</pre>
                                            </div>
                                        </div>
                                        <input type="hidden" name="final_headers" id="final_headers" value='{{ old('final_headers', '{}') }}'>
                                     </div>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="w3-row w3-section">
                                <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-file-code-o"></i></div>
                                <div class="w3-rest">
                                    <textarea class="w3-input w3-border @error('request_body') w3-border-red @enderror" name="request_body" rows="6" placeholder="Request Body (JSON)">{{ old('request_body', $old['request_body'] ?? '') }}</textarea>
                                    @error('request_body') <div class="w3-text-red w3-small">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="w3-center w3-padding-16">
                                <a href="{{ route('test-results.index') }}" class="w3-button w3-red w3-margin-right">Cancel</a>
                                <button type="submit" class="w3-button w3-blue w3-ripple"><i class="fa fa-play"></i> Start Test</button>
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
