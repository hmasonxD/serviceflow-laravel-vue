<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\WorkOrderPriority;
use App\Enums\WorkOrderStatus;
use App\Models\Customer;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'ServiceFlow Admin',
            'email' => 'admin@serviceflow.test',
            'role' => UserRole::Admin,
        ]);

        $primaryTechnician = User::factory()->create([
            'name' => 'Alex Morgan',
            'email' => 'technician@serviceflow.test',
            'role' => UserRole::Technician,
        ]);

        $secondTechnician = User::factory()->create([
            'name' => 'Jordan Lee',
            'email' => 'jordan@serviceflow.test',
            'role' => UserRole::Technician,
        ]);

        $customers = Customer::factory()
            ->count(20)
            ->create([
                'created_by' => $admin->id,
            ]);

        $draft = WorkOrder::factory()->create([
            'customer_id' => $customers[0]->id,
            'created_by' => $admin->id,
            'assigned_to' => null,
            'title' => 'Commercial HVAC inspection',
            'description' => 'Inspect rooftop HVAC units and document required maintenance.',
            'status' => WorkOrderStatus::Draft,
            'priority' => WorkOrderPriority::Normal,
            'subtotal' => 275,
            'tax' => 13.75,
            'total' => 288.75,
        ]);

        $scheduled = WorkOrder::factory()->create([
            'customer_id' => $customers[1]->id,
            'created_by' => $admin->id,
            'assigned_to' => $primaryTechnician->id,
            'title' => 'Replace circulation pump',
            'description' => 'Replace failed circulation pump and verify system pressure.',
            'status' => WorkOrderStatus::Scheduled,
            'priority' => WorkOrderPriority::High,
            'scheduled_at' => now()->addDay()->setTime(9, 0),
            'subtotal' => 450,
            'tax' => 22.50,
            'total' => 472.50,
        ]);

        $inProgress = WorkOrder::factory()->create([
            'customer_id' => $customers[2]->id,
            'created_by' => $admin->id,
            'assigned_to' => $secondTechnician->id,
            'title' => 'Emergency boiler repair',
            'description' => 'Diagnose intermittent boiler shutdown and restore reliable operation.',
            'status' => WorkOrderStatus::InProgress,
            'priority' => WorkOrderPriority::Urgent,
            'scheduled_at' => now()->subHours(3),
            'started_at' => now()->subHours(2),
            'subtotal' => 625,
            'tax' => 31.25,
            'total' => 656.25,
        ]);

        $completed = WorkOrder::factory()->create([
            'customer_id' => $customers[3]->id,
            'created_by' => $admin->id,
            'assigned_to' => $primaryTechnician->id,
            'title' => 'Annual furnace maintenance',
            'description' => 'Complete annual inspection, cleaning, and safety checks.',
            'status' => WorkOrderStatus::Completed,
            'priority' => WorkOrderPriority::Low,
            'scheduled_at' => now()->subDays(2)->setTime(10, 0),
            'started_at' => now()->subDays(2)->setTime(10, 4),
            'completed_at' => now()->subDays(2)->setTime(11, 25),
            'subtotal' => 180,
            'tax' => 9,
            'total' => 189,
        ]);

        $cancelled = WorkOrder::factory()->create([
            'customer_id' => $customers[4]->id,
            'created_by' => $admin->id,
            'assigned_to' => $secondTechnician->id,
            'title' => 'Thermostat replacement',
            'description' => 'Replace outdated thermostat with customer-provided unit.',
            'status' => WorkOrderStatus::Cancelled,
            'priority' => WorkOrderPriority::Normal,
            'scheduled_at' => now()->addDays(3)->setTime(13, 30),
            'subtotal' => 125,
            'tax' => 6.25,
            'total' => 131.25,
        ]);

        WorkOrder::factory()
            ->count(10)
            ->create([
                'created_by' => $admin->id,
            ]);

        $draft->activities()->create([
            'user_id' => $admin->id,
            'type' => 'created',
            'description' => 'Work order created.',
        ]);

        $scheduled->activities()->createMany([
            [
                'user_id' => $admin->id,
                'type' => 'created',
                'description' => 'Work order created.',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'user_id' => $admin->id,
                'type' => 'status_changed',
                'from_status' => WorkOrderStatus::Draft->value,
                'to_status' => WorkOrderStatus::Scheduled->value,
                'description' => 'Status changed from Draft to Scheduled.',
            ],
        ]);

        $inProgress->activities()->createMany([
            [
                'user_id' => $admin->id,
                'type' => 'created',
                'description' => 'Work order created.',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'user_id' => $admin->id,
                'type' => 'status_changed',
                'from_status' => WorkOrderStatus::Draft->value,
                'to_status' => WorkOrderStatus::Scheduled->value,
                'description' => 'Status changed from Draft to Scheduled.',
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ],
            [
                'user_id' => $secondTechnician->id,
                'type' => 'status_changed',
                'from_status' => WorkOrderStatus::Scheduled->value,
                'to_status' => WorkOrderStatus::InProgress->value,
                'description' => 'Status changed from Scheduled to In Progress.',
            ],
        ]);

        $completed->activities()->createMany([
            [
                'user_id' => $admin->id,
                'type' => 'created',
                'description' => 'Work order created.',
            ],
            [
                'user_id' => $admin->id,
                'type' => 'status_changed',
                'from_status' => WorkOrderStatus::Draft->value,
                'to_status' => WorkOrderStatus::Scheduled->value,
                'description' => 'Status changed from Draft to Scheduled.',
            ],
            [
                'user_id' => $primaryTechnician->id,
                'type' => 'status_changed',
                'from_status' => WorkOrderStatus::Scheduled->value,
                'to_status' => WorkOrderStatus::InProgress->value,
                'description' => 'Status changed from Scheduled to In Progress.',
            ],
            [
                'user_id' => $primaryTechnician->id,
                'type' => 'status_changed',
                'from_status' => WorkOrderStatus::InProgress->value,
                'to_status' => WorkOrderStatus::Completed->value,
                'description' => 'Status changed from In Progress to Completed.',
            ],
        ]);

        $cancelled->activities()->createMany([
            [
                'user_id' => $admin->id,
                'type' => 'created',
                'description' => 'Work order created.',
            ],
            [
                'user_id' => $admin->id,
                'type' => 'status_changed',
                'from_status' => WorkOrderStatus::Draft->value,
                'to_status' => WorkOrderStatus::Cancelled->value,
                'description' => 'Status changed from Draft to Cancelled.',
            ],
        ]);
    }
}
