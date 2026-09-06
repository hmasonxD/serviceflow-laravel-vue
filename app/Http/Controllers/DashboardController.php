<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Enums\WorkOrderPriority;
use App\Enums\WorkOrderStatus;
use App\Models\Customer;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();

        $workOrders = WorkOrder::query()
            ->when(
                $user->role === UserRole::Technician,
                fn ($query) => $query->where('assigned_to', $user->id),
            );

        $recentWorkOrders = (clone $workOrders)
            ->with([
                'customer:id,name,company',
                'assignee:id,name',
            ])
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'metrics' => [
                'customers' => Customer::query()->count(),
                'openWorkOrders' => (clone $workOrders)
                    ->whereNotIn('status', [
                        WorkOrderStatus::Completed,
                        WorkOrderStatus::Cancelled,
                    ])
                    ->count(),
                'scheduledWorkOrders' => (clone $workOrders)
                    ->where('status', WorkOrderStatus::Scheduled)
                    ->count(),
                'urgentWorkOrders' => (clone $workOrders)
                    ->where('priority', WorkOrderPriority::Urgent)
                    ->whereNotIn('status', [
                        WorkOrderStatus::Completed,
                        WorkOrderStatus::Cancelled,
                    ])
                    ->count(),
            ],
            'recentWorkOrders' => $recentWorkOrders,
        ]);
    }
}
