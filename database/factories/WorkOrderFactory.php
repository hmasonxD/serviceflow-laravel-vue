<?php

namespace Database\Factories;

use App\Enums\WorkOrderPriority;
use App\Enums\WorkOrderStatus;
use App\Models\Customer;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkOrder>
 */
class WorkOrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'assigned_to' => null,
            'created_by' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'status' => WorkOrderStatus::Draft,
            'priority' => WorkOrderPriority::Normal,
            'scheduled_at' => null,
            'started_at' => null,
            'completed_at' => null,
            'subtotal' => 0,
            'tax' => 0,
            'total' => 0,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
