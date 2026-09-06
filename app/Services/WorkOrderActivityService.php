<?php

namespace App\Services;

use App\Enums\WorkOrderStatus;
use App\Models\User;
use App\Models\WorkOrder;
use App\Models\WorkOrderActivity;

class WorkOrderActivityService
{
    public function recordCreated(
        WorkOrder $workOrder,
        User $user,
    ): WorkOrderActivity {
        return $workOrder->activities()->create([
            'user_id' => $user->id,
            'type' => 'created',
            'description' => 'Work order created.',
        ]);
    }

    public function recordUpdated(
        WorkOrder $workOrder,
        User $user,
    ): WorkOrderActivity {
        return $workOrder->activities()->create([
            'user_id' => $user->id,
            'type' => 'updated',
            'description' => 'Work order details updated.',
        ]);
    }

    public function recordStatusChanged(
        WorkOrder $workOrder,
        User $user,
        WorkOrderStatus $from,
        WorkOrderStatus $to,
    ): WorkOrderActivity {
        return $workOrder->activities()->create([
            'user_id' => $user->id,
            'type' => 'status_changed',
            'from_status' => $from->value,
            'to_status' => $to->value,
            'description' => sprintf(
                'Status changed from %s to %s.',
                $this->label($from),
                $this->label($to),
            ),
        ]);
    }

    private function label(WorkOrderStatus $status): string
    {
        return match ($status) {
            WorkOrderStatus::Draft => 'Draft',
            WorkOrderStatus::Scheduled => 'Scheduled',
            WorkOrderStatus::InProgress => 'In Progress',
            WorkOrderStatus::Completed => 'Completed',
            WorkOrderStatus::Cancelled => 'Cancelled',
        };
    }
}
