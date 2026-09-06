<?php

namespace App\Services;

use App\Enums\WorkOrderStatus;
use App\Models\WorkOrder;
use DomainException;
use Illuminate\Support\Carbon;

class WorkOrderStatusService
{
    /**
     * @var array<string, list<WorkOrderStatus>>
     */
    private const TRANSITIONS = [
        WorkOrderStatus::Draft->value => [
            WorkOrderStatus::Scheduled,
            WorkOrderStatus::Cancelled,
        ],
        WorkOrderStatus::Scheduled->value => [
            WorkOrderStatus::InProgress,
            WorkOrderStatus::Cancelled,
        ],
        WorkOrderStatus::InProgress->value => [
            WorkOrderStatus::Completed,
            WorkOrderStatus::Cancelled,
        ],
        WorkOrderStatus::Completed->value => [],
        WorkOrderStatus::Cancelled->value => [],
    ];

    public function canTransition(
        WorkOrderStatus $from,
        WorkOrderStatus $to,
    ): bool {
        return in_array(
            $to,
            self::TRANSITIONS[$from->value],
            true,
        );
    }

    /**
     * @return list<WorkOrderStatus>
     */
    public function allowedTransitions(WorkOrderStatus $from): array
    {
        return self::TRANSITIONS[$from->value];
    }

    public function transition(
        WorkOrder $workOrder,
        WorkOrderStatus $to,
    ): WorkOrder {
        $from = $workOrder->status;

        if (! $this->canTransition($from, $to)) {
            throw new DomainException(
                sprintf(
                    'Invalid work order status transition from %s to %s.',
                    $from->value,
                    $to->value,
                ),
            );
        }

        $workOrder->status = $to;

        match ($to) {
            WorkOrderStatus::InProgress => $workOrder->started_at ??= Carbon::now(),
            WorkOrderStatus::Completed => $workOrder->completed_at ??= Carbon::now(),

            WorkOrderStatus::Draft,
            WorkOrderStatus::Scheduled,
            WorkOrderStatus::Cancelled => null,
        };

        $workOrder->save();

        return $workOrder->refresh();
    }
}
