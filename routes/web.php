<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestResultController;

// Redirect root to test-results
Route::get('/', function () {
    return redirect()->route('test-results.index');
});

// Test Results Routes
Route::get('test-results/bulk', [TestResultController::class, 'bulkCreate'])->name('test-results.bulk.create');
Route::post('test-results/bulk', [TestResultController::class, 'bulkStore'])->name('test-results.bulk.store');
Route::resource('test-results', TestResultController::class)->except(['index']);
Route::get('/test-results', [TestResultController::class, 'index'])->name('test-results.index');
Route::post('test-results/export', [TestResultController::class, 'export'])->name('test-results.export');
Route::get('test-results/{testResult}/pdf', [TestResultController::class, 'downloadPdf'])->name('test-results.pdf');
Route::get('test-results/{testResult}/status', [TestResultController::class, 'status'])->name('test-results.status');
Route::delete('test-results/{testResult}', [TestResultController::class, 'destroy'])->name('test-results.destroy');
