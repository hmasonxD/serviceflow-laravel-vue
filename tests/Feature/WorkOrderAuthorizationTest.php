<?php

use App\Enums\UserRole;
use App\Enums\WorkOrderStatus;
use App\Models\User;
use App\Models\WorkOrder;

test('administrator can transition any work order', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $workOrder = WorkOrder::factory()->create([
        'status' => WorkOrderStatus::Draft,
    ]);

    $this
        ->actingAs($admin)
        ->patch(route('work-orders.transition', $workOrder), [
            'status' => WorkOrderStatus::Scheduled->value,
        ])
        ->assertRedirect(route('work-orders.show', $workOrder));

    expect($workOrder->fresh()->status)
        ->toBe(WorkOrderStatus::Scheduled);
});

test('assigned technician can transition their work order', function () {
    $technician = User::factory()->create([
        'role' => UserRole::Technician,
    ]);

    $workOrder = WorkOrder::factory()->create([
        'assigned_to' => $technician->id,
        'status' => WorkOrderStatus::Scheduled,
    ]);

    $this
        ->actingAs($technician)
        ->patch(route('work-orders.transition', $workOrder), [
            'status' => WorkOrderStatus::InProgress->value,
        ])
        ->assertRedirect(route('work-orders.show', $workOrder));

    expect($workOrder->fresh()->status)
        ->toBe(WorkOrderStatus::InProgress);
});

test('technician cannot transition an unassigned work order', function () {
    $technician = User::factory()->create([
        'role' => UserRole::Technician,
    ]);

    $workOrder = WorkOrder::factory()->create([
        'status' => WorkOrderStatus::Scheduled,
    ]);

    $this
        ->actingAs($technician)
        ->patch(route('work-orders.transition', $workOrder), [
            'status' => WorkOrderStatus::InProgress->value,
        ])
        ->assertForbidden();
});

test('invalid status transition is rejected', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $workOrder = WorkOrder::factory()->create([
        'status' => WorkOrderStatus::Draft,
    ]);

    $this
        ->actingAs($admin)
        ->from(route('work-orders.show', $workOrder))
        ->patch(route('work-orders.transition', $workOrder), [
            'status' => WorkOrderStatus::Completed->value,
        ])
        ->assertRedirect(route('work-orders.show', $workOrder))
        ->assertSessionHasErrors('status');

    expect($workOrder->fresh()->status)
        ->toBe(WorkOrderStatus::Draft);
});
