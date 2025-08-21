<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;

class TestResult extends Model
{
    use HasFactory, Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'url',
        'protocol',
        'method',
        'concurrency_level',
        'request_headers',
        'request_body',
        'total_requests',
        'successful_requests',
        'failed_requests',
        'average_response_time',
        'response_times',
        'error_details',
        'status',
        'progress',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'request_headers' => 'array',
        'request_body' => 'array',
        'response_times' => 'array',
        'error_details' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'concurrency_level' => 'integer',
        'total_requests' => 'integer',
        'successful_requests' => 'integer',
        'failed_requests' => 'integer',
        'average_response_time' => 'float',
        'progress' => 'integer',
    ];

    /**
     * The attributes that are sortable.
     *
     * @var array
     */
    public $sortable = [
        'id',
        'url',
        'method',
        'concurrency_level',
        'average_response_time',
        'created_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['success_rate'];

    protected $attributes = [
        'status' => 'pending',
        'progress' => 0,
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_QUEUED = 'queued';
    public const STATUS_RUNNING = 'running';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    /**
     * Get the status class for the test result.
     *
     * @return string
     */
    public function getStatusClassAttribute()
    {
        return [
            self::STATUS_PENDING => 'bg-secondary',
            self::STATUS_RUNNING => 'bg-info',
            self::STATUS_COMPLETED => 'bg-success',
            self::STATUS_FAILED => 'bg-danger',
        ][$this->status] ?? 'bg-secondary';
    }

    /**
     * Get the success rate attribute.
     *
     * @return float
     */
    public function getSuccessRateAttribute()
    {
        if ($this->total_requests === 0) {
            return 0;
        }
        return round(($this->successful_requests / $this->total_requests) * 100, 2);
    }
}
