@extends('layouts.w3app')

@section('content')
<div class="w3-container">
    <div class="w3-row">
        <div class="w3-col m12">
            <div class="w3-card-4 w3-margin-top">
                <div class="w3-container w3-padding-16">
                    <h3 class="w3-center w3-padding-16"><i class="fa fa-cubes"></i> Bulk Endpoint Testing</h3>
                    
                    <form method="POST" action="{{ route('test-results.bulk.store') }}" class="w3-container">
                        @csrf
                        
                        <div class="w3-section">
                            <!-- URLs Section -->
                            <div class="w3-row w3-section">
                                <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-globe"></i></div>
                                <div class="w3-rest">
                                    <label class="w3-text-grey w3-small">URLs (one per line)</label>
                                    <textarea class="w3-input w3-border @error('urls') w3-border-red @enderror" name="urls" rows="5" placeholder="example.com/api/v1&#10;google.com&#10;http://localhost:8000/test" required>{{ old('urls') }}</textarea>
                                    <div class="w3-small w3-text-grey">If no protocol is provided, HTTPS will be used by default.</div>
                                    @error('urls') <div class="w3-text-red w3-small">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Shared Configurations -->
                            <div class="w3-row w3-section">
                                <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-cog"></i></div>
                                <div class="w3-rest">
                                    <div class="w3-row-padding" style="margin:0 -16px">
                                        <div class="w3-third">
                                            <label class="w3-text-grey w3-small">Method</label>
                                            <select class="w3-select w3-border @error('method') w3-border-red @enderror" name="method" required>
                                                <option value="GET" {{ old('method') === 'GET' ? 'selected' : '' }}>GET</option>
                                                <option value="POST" {{ old('method') === 'POST' ? 'selected' : '' }}>POST</option>
                                                <option value="PUT" {{ old('method') === 'PUT' ? 'selected' : '' }}>PUT</option>
                                                <option value="DELETE" {{ old('method') === 'DELETE' ? 'selected' : '' }}>DELETE</option>
                                                <option value="PATCH" {{ old('method') === 'PATCH' ? 'selected' : '' }}>PATCH</option>
                                            </select>
                                        </div>
                                        <div class="w3-third">
                                            <label class="w3-text-grey w3-small">Concurrency</label>
                                            <input class="w3-input w3-border @error('concurrency_level') w3-border-red @enderror" name="concurrency_level" type="number" value="{{ old('concurrency_level', 1) }}" min="1" required>
                                        </div>
                                        <div class="w3-third">
                                            <label class="w3-text-grey w3-small">Timeout (s)</label>
                                            <input class="w3-input w3-border @error('timeout') w3-border-red @enderror" name="timeout" type="number" value="{{ old('timeout', 60) }}" min="1" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Headers Config -->
                            <div class="w3-row w3-section">
                                <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-list-ul"></i></div>
                                <div class="w3-rest">
                                     <div class="w3-card w3-light-grey w3-padding">
                                        <p class="w3-small w3-margin-0">Shared Headers</p>
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
                                        <textarea id="request_headers_input" class="w3-input w3-border w3-margin-top w3-tiny" 
                                                  rows="2" placeholder='Additional JSON Headers: {"X-Key": "Value"}'>{{ old('request_headers', '{}') }}</textarea>
                                        
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
                                    <textarea class="w3-input w3-border @error('request_body') w3-border-red @enderror" name="request_body" rows="4" placeholder="Request Body (JSON for all requests)">{{ old('request_body') }}</textarea>
                                    @error('request_body') <div class="w3-text-red w3-small">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="w3-center w3-padding-16">
                                <a href="{{ route('test-results.index') }}" class="w3-button w3-red w3-margin-right">Cancel</a>
                                <button type="submit" class="w3-button w3-blue w3-ripple"><i class="fa fa-play"></i> Run Bulk Test</button>
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
    document.addEventListener('DOMContentLoaded', function() {
        const contentTypeSelect = document.getElementById('content_type');
        const authorizationInput = document.getElementById('authorization');
        const requestHeadersTextarea = document.getElementById('request_headers_input');
        const headersPreview = document.getElementById('headers_preview');
        const finalHeadersInput = document.getElementById('final_headers');
        
        function updateHeaders() {
            try {
                const contentType = contentTypeSelect.value;
                const authToken = authorizationInput.value;
                let additionalHeaders = {};
                
                try {
                    additionalHeaders = JSON.parse(requestHeadersTextarea.value || '{}');
                } catch (e) {
                    console.error('Invalid JSON in additional headers');
                }
                
                const headers = { ...additionalHeaders };
                if (contentType) headers['Content-Type'] = contentType;
                if (authToken) headers['Authorization'] = `Bearer ${authToken}`;
                
                headersPreview.textContent = Object.keys(headers).length > 0 
                    ? JSON.stringify(headers, null, 2) 
                    : 'No headers defined';
                
                finalHeadersInput.value = JSON.stringify(headers);
            } catch (error) {
                console.error('Error updating headers:', error);
            }
        }
        
        contentTypeSelect.addEventListener('change', updateHeaders);
        authorizationInput.addEventListener('input', updateHeaders);
        requestHeadersTextarea.addEventListener('input', updateHeaders);
        
        updateHeaders();
    });
</script>
@endpush
