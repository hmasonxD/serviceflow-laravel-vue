<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WorkOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? to_route('dashboard')
        : to_route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)
        ->name('dashboard');

    Route::resource('customers', CustomerController::class)
        ->except('destroy');

    Route::patch(
        'work-orders/{workOrder}/status',
        [WorkOrderController::class, 'transition'],
    )->name('work-orders.transition');

    Route::resource('work-orders', WorkOrderController::class)
        ->except('destroy');
});

require __DIR__.'/settings.php';
