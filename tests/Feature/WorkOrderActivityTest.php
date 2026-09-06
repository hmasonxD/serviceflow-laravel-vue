<?php

use App\Enums\UserRole;
use App\Enums\WorkOrderStatus;
use App\Models\Customer;
use App\Models\User;
use App\Models\WorkOrder;

test('creating a work order records activity', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $workOrder = WorkOrder::factory()->make();

    $this
        ->actingAs($admin)
        ->post(route('work-orders.store'), [
            'customer_id' => $workOrder->customer()->associate(
                Customer::factory()->create()
            )->customer_id,
            'assigned_to' => null,
            'title' => 'Replace circulation pump',
            'description' => null,
            'priority' => 'normal',
            'scheduled_at' => null,
            'subtotal' => 100,
            'tax' => 5,
            'notes' => null,
        ])
        ->assertRedirect();

    $created = WorkOrder::query()->latest('id')->firstOrFail();

    expect($created->activities)
        ->toHaveCount(1)
        ->and($created->activities->first()->type)
        ->toBe('created');
});

test('updating a work order records activity', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $workOrder = WorkOrder::factory()->create();

    $this
        ->actingAs($admin)
        ->patch(route('work-orders.update', $workOrder), [
            'customer_id' => $workOrder->customer_id,
            'assigned_to' => null,
            'title' => 'Updated work order',
            'description' => null,
            'priority' => 'high',
            'scheduled_at' => null,
            'subtotal' => 200,
            'tax' => 10,
            'notes' => null,
        ])
        ->assertRedirect(route('work-orders.show', $workOrder));

    expect($workOrder->fresh()->activities)
        ->toHaveCount(1)
        ->and($workOrder->fresh()->activities->first()->type)
        ->toBe('updated');
});

test('status transition records old and new status', function () {
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

    $activity = $workOrder
        ->fresh()
        ->activities
        ->first();

    expect($activity->type)
        ->toBe('status_changed')
        ->and($activity->from_status)
        ->toBe('draft')
        ->and($activity->to_status)
        ->toBe('scheduled');
});
