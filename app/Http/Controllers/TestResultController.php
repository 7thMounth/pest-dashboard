<?php

namespace App\Http\Controllers;

use App\Models\TestResult;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as ExcelFacade;
use Kyslik\ColumnSortable\Sortable;
use Barryvdh\DomPDF\Facade\Pdf;

class TestResultController extends Controller
{
    /**
     * Display a listing of the test results.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = TestResult::query();
        
        // Apply date range filter
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        // Apply sorting and pagination
        $testResults = $query->sortable(['created_at' => 'desc'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
        
        return view('test-results.index', [
            'testResults' => $testResults,
            'perPage' => (int)$perPage,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function create(Request $request)
    {
        $defaults = [
            'url' => $request->get('url', ''),
            'protocol' => $request->get('protocol', 'https'),
            'method' => $request->get('method', 'GET'),
            'concurrency_level' => $request->get('concurrency_level', 1),
            'is_clone' => $request->has('clone'),
        ];
        
        // If this is a clone, we'll also include the request headers and body
        if ($request->has('clone') && $request->has('test_result_id')) {
            $originalTest = TestResult::find($request->get('test_result_id'));
            if ($originalTest) {
                $defaults['request_headers'] = json_encode($originalTest->request_headers, JSON_PRETTY_PRINT);
                $defaults['request_body'] = is_array($originalTest->request_body) 
                    ? json_encode($originalTest->request_body, JSON_PRETTY_PRINT) 
                    : $originalTest->request_body;
            }
        }
        
        return view('test-results.create', ['old' => $defaults]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    // Remove any protocol if present
                    $value = preg_replace('#^https?://#', '', $value);
                    
                    // Check if it's an IP address (v4 or v6)
                    $isIp = filter_var($value, FILTER_VALIDATE_IP) !== false;
                    
                    // Check if it's a domain with optional port and path
                    $isDomain = preg_match('/^([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,}|localhost)(:[0-9]+)?(\/.*)?$/i', $value);
                    
                    // Check if it's an IP with port and/or path
                    $isIpWithPath = preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}(:\d+)?(\/.*)?$/', $value);
                    
                    if (!$isIp && !$isDomain && !$isIpWithPath) {
                        $fail('The URL format is invalid. Examples: example.com, 192.168.1.1, localhost:3000, or api.example.com/path');
                    }
                },
            ],
            'protocol' => 'required|in:http,https',
            'method' => 'required|in:GET,POST,PUT,DELETE,PATCH',
            'concurrency_level' => 'required|integer|min:1',
            'final_headers' => 'required|json',
            'request_body' => 'nullable|json',
        ]);
        
        // Clean the URL by removing any protocol and leading/trailing slashes
        $validated['url'] = trim(preg_replace('#^https?://#', '', $validated['url']), '/');

        // Parse the final headers JSON
        $validated['request_headers'] = json_decode($validated['final_headers'], true);
        unset($validated['final_headers']);

        // Parse request body if provided
        if (!empty($validated['request_body'])) {
            $validated['request_body'] = json_decode($validated['request_body'], true);
        }

        // Set initial values for all required fields
        $testData = array_merge($validated, [
            'status' => TestResult::STATUS_QUEUED,
            'progress' => 0,
            'total_requests' => 0,
            'successful_requests' => 0,
            'failed_requests' => 0,
            'average_response_time' => 0,
            'response_times' => [],
            'error_details' => []
        ]);
        
        $testResult = TestResult::create($testData);
        
        // Dispatch job to run the test in the background
        dispatch(function () use ($testResult) {
            $this->runConcurrencyTest($testResult);
        })->afterResponse();

        return redirect()->route('test-results.show', $testResult);
    }

    public function show(TestResult $testResult)
    {
        // Convert response times to a collection and paginate
        $responseTimes = collect($testResult->response_times ?? []);
        $perPage = request()->input('per_page', 20); // Get per_page from request or default to 20
        $currentPage = request()->get('page', 1);
        $paginatedResponseTimes = new \Illuminate\Pagination\LengthAwarePaginator(
            $responseTimes->forPage($currentPage, $perPage),
            $responseTimes->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(), 
                'query' => request()->query(),
                'pageName' => 'page'
            ]
        );
        
        // Add per_page to the paginator's query parameters
        $paginatedResponseTimes->appends(['per_page' => $perPage]);
        
        return view('test-results.show', [
            'testResult' => $testResult,
            'paginatedResponseTimes' => $paginatedResponseTimes
        ]);
    }

    /**
     * Export test results to Excel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Maatwebsite\Excel\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $query = TestResult::query();
        
        // Apply date range filter if provided
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        
        $export = new \App\Exports\TestResultsExport($query);
        
        return ExcelFacade::download(
            $export, 
            'test-results-' . now()->format('Y-m-d-H-i-s') . '.xlsx'
        );
    }
    
    /**
     * Export a test result to PDF.
     *
     * @param  \App\Models\TestResult  $testResult
     * @return \Barryvdh\DomPDF\PDF
     */
    public function downloadPdf(TestResult $testResult)
    {
        $pdf = PDF::loadView('test-results.pdf', compact('testResult'));
        return $pdf->download('test-result-' . $testResult->id . '.pdf');
    }
    
    public function status(TestResult $testResult)
    {
        return response()->json([
            'status' => $testResult->status,
            'progress' => $testResult->progress,
            'successful_requests' => $testResult->successful_requests,
            'failed_requests' => $testResult->failed_requests,
            'average_response_time' => $testResult->average_response_time,
            'is_completed' => in_array($testResult->status, [TestResult::STATUS_COMPLETED, TestResult::STATUS_FAILED]),
        ]);
    }

    /**
     * Remove the specified test result from storage.
     *
     * @param  \App\Models\TestResult  $testResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(TestResult $testResult)
    {
        $testResult->delete();

        return redirect()->route('test-results.index')
            ->with('success', 'Test result deleted successfully');
    }

    private function runConcurrencyTest(TestResult $testResult)
    {
        try {
            // Update status to running
            $testResult->update([
                'status' => TestResult::STATUS_RUNNING,
                'progress' => 0,
            ]);

            $client = new Client([
                'timeout' => 30,
                'connect_timeout' => 10,
                'http_errors' => false
            ]);

            // If URL already starts with http:// or https://, use it as is, otherwise prepend protocol
            $url = (strpos($testResult->url, 'http') === 0) 
                ? $testResult->url 
                : $testResult->protocol . '://' . ltrim($testResult->url, '/');

            $responseTimes = [];
            $errors = [];
            $successful = 0;
            $failed = 0;
            $totalTime = 0;
            $batchSize = 100; // Process in batches to update progress more frequently
            $totalRequests = $testResult->concurrency_level;

            for ($i = 0; $i < $totalRequests; $i += $batchSize) {
                $promises = [];
                $batchEnd = min($i + $batchSize, $totalRequests);
                
                // Create batch of requests
                for ($j = $i; $j < $batchEnd; $j++) {
                    $promises[] = $client->requestAsync(
                        $testResult->method,
                        $url,
                        [
                            'headers' => $testResult->request_headers,
                            'json' => $testResult->request_body,
                        ]
                    )->then(
                        function ($response) use (&$responseTimes, &$successful, &$totalTime, $j) {
                            $time = $response->getHeaderLine('total_time') ?: 0;
                            $responseTimes[] = $time;
                            $totalTime += $time;
                            $successful++;
                        },
                        function ($reason) use (&$errors, &$failed, $j) {
                            $errors[] = [
                                'request' => $j + 1,
                                'error' => $reason->getMessage()
                            ];
                            $failed++;
                        }
                    );
                }

                // Wait for the batch to complete
                \GuzzleHttp\Promise\Utils::settle($promises)->wait();

                // Update progress
                $progress = (int)(($batchEnd / $totalRequests) * 100);
                $testResult->update([
                    'progress' => $progress,
                    'successful_requests' => $successful,
                    'failed_requests' => $failed,
                    'average_response_time' => $successful > 0 ? ($totalTime / $successful) : 0,
                    'response_times' => $responseTimes,
                    'error_details' => $errors,
                ]);
            }

            // Mark test as completed
            $testResult->update([
                'status' => TestResult::STATUS_COMPLETED,
                'progress' => 100,
                'total_requests' => $testResult->concurrency_level,
                'successful_requests' => $successful,
                'failed_requests' => $failed,
                'average_response_time' => $successful > 0 ? ($totalTime / $successful) : 0,
                'response_times' => $responseTimes,
                'error_details' => $errors,
            ]);

        } catch (\Exception $e) {
            // Mark test as failed if an exception occurs
            $testResult->update([
                'status' => TestResult::STATUS_FAILED,
                'error_details' => array_merge($testResult->error_details ?? [], [
                    ['error' => 'Test failed: ' . $e->getMessage()]
                ])
            ]);
        }
    }
}
