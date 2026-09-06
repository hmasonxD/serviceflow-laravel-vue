<?php

use App\Enums\WorkOrderStatus;
use App\Models\WorkOrder;
use App\Services\WorkOrderStatusService;
use DomainException;

test('valid work order transitions are allowed', function () {
    $service = app(WorkOrderStatusService::class);

    expect($service->canTransition(
        WorkOrderStatus::Draft,
        WorkOrderStatus::Scheduled,
    ))->toBeTrue();

    expect($service->canTransition(
        WorkOrderStatus::Scheduled,
        WorkOrderStatus::InProgress,
    ))->toBeTrue();

    expect($service->canTransition(
        WorkOrderStatus::InProgress,
        WorkOrderStatus::Completed,
    ))->toBeTrue();
});

test('invalid work order transitions are rejected', function () {
    $service = app(WorkOrderStatusService::class);

    expect($service->canTransition(
        WorkOrderStatus::Draft,
        WorkOrderStatus::Completed,
    ))->toBeFalse();

    expect($service->canTransition(
        WorkOrderStatus::Completed,
        WorkOrderStatus::InProgress,
    ))->toBeFalse();

    expect($service->canTransition(
        WorkOrderStatus::Cancelled,
        WorkOrderStatus::Scheduled,
    ))->toBeFalse();
});

test('transition updates status and started timestamp', function () {
    $workOrder = WorkOrder::factory()->create([
        'status' => WorkOrderStatus::Scheduled,
        'started_at' => null,
    ]);

    $service = app(WorkOrderStatusService::class);

    $updated = $service->transition(
        $workOrder,
        WorkOrderStatus::InProgress,
    );

    expect($updated->status)
        ->toBe(WorkOrderStatus::InProgress)
        ->and($updated->started_at)
        ->not->toBeNull();
});

test('transition to completed sets completed timestamp', function () {
    $workOrder = WorkOrder::factory()->create([
        'status' => WorkOrderStatus::InProgress,
        'completed_at' => null,
    ]);

    $service = app(WorkOrderStatusService::class);

    $updated = $service->transition(
        $workOrder,
        WorkOrderStatus::Completed,
    );

    expect($updated->status)
        ->toBe(WorkOrderStatus::Completed)
        ->and($updated->completed_at)
        ->not->toBeNull();
});

test('invalid transition throws a domain exception', function () {
    $workOrder = WorkOrder::factory()->create([
        'status' => WorkOrderStatus::Draft,
    ]);

    $service = app(WorkOrderStatusService::class);

    expect(fn () => $service->transition(
        $workOrder,
        WorkOrderStatus::Completed,
    ))->toThrow(DomainException::class);
});
