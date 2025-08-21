<?php

namespace App\Exports;

use App\Models\TestResult;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TestResultsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $query;

    /**
     * Create a new export instance.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function __construct($query = null)
    {
        $this->query = $query ?: TestResult::query();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'URL',
            'Protocol',
            'Method',
            'Concurrency Level',
            'Total Requests',
            'Successful Requests',
            'Failed Requests',
            'Success Rate (%)',
            'Average Response Time (s)',
            'Created At',
            'Updated At',
            'Request Headers',
            'Request Body',
            'Response Times',
            'Error Details'
        ];
    }

    public function map($testResult): array
    {
        return [
            $testResult->id,
            $testResult->url,
            $testResult->protocol,
            $testResult->method,
            $testResult->concurrency_level,
            $testResult->total_requests,
            $testResult->successful_requests,
            $testResult->failed_requests,
            $testResult->total_requests > 0 ? number_format(($testResult->successful_requests / $testResult->total_requests) * 100, 2) : 0,
            number_format($testResult->average_response_time, 2),
            $testResult->created_at->format('Y-m-d H:i:s'),
            $testResult->updated_at->format('Y-m-d H:i:s'),
            json_encode($testResult->request_headers),
            json_encode($testResult->request_body),
            json_encode($testResult->response_times),
            json_encode($testResult->error_details)
        ];
    }
}
