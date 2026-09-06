<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Enums\WorkOrderStatus;
use App\Http\Requests\StoreWorkOrderRequest;
use App\Http\Requests\TransitionWorkOrderStatusRequest;
use App\Http\Requests\UpdateWorkOrderRequest;
use App\Models\Customer;
use App\Models\User;
use App\Models\WorkOrder;
use App\Services\WorkOrderActivityService;
use App\Services\WorkOrderStatusService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class WorkOrderController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', WorkOrder::class);

        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->trim()->toString();

        $user = $request->user();

        $workOrders = WorkOrder::query()
            ->with([
                'customer:id,name,company',
                'assignee:id,name',
            ])
            ->when(
                $user->role === UserRole::Technician,
                fn ($query) => $query->where('assigned_to', $user->id),
            )
            ->when(
                $search !== '',
                fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhereHas(
                            'customer',
                            fn ($query) => $query
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('company', 'like', "%{$search}%"),
                        );
                }),
            )
            ->when(
                $status !== '',
                fn ($query) => $query->where('status', $status),
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('work-orders/Index', [
            'workOrders' => $workOrders,
            'filters' => [
                'search' => $search,
                'status' => $status,
            ],
            'can' => [
                'create' => Gate::allows('create', WorkOrder::class),
            ],
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', WorkOrder::class);

        return Inertia::render('work-orders/Create', [
            'customers' => Customer::query()
                ->orderBy('name')
                ->get(['id', 'name', 'company']),
            'technicians' => User::query()
                ->where('role', UserRole::Technician)
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function store(
        StoreWorkOrderRequest $request,
        WorkOrderActivityService $activityService,
    ): RedirectResponse {
        Gate::authorize('create', WorkOrder::class);

        $validated = $request->validated();

        $workOrder = WorkOrder::create([
            ...$validated,
            'created_by' => $request->user()->id,
            'total' => (float) $validated['subtotal']
                + (float) $validated['tax'],
        ]);

        $activityService->recordCreated(
            $workOrder,
            $request->user(),
        );

        return to_route('work-orders.show', $workOrder)
            ->with('success', 'Work order created successfully.');
    }

    public function show(
        WorkOrder $workOrder,
        WorkOrderStatusService $statusService,
    ): Response {
        Gate::authorize('view', $workOrder);

        $workOrder->load([
            'customer',
            'assignee:id,name,email',
            'creator:id,name',
            'activities.user:id,name',
        ]);

        return Inertia::render('work-orders/Show', [
            'workOrder' => $workOrder,
            'can' => [
                'update' => Gate::allows('update', $workOrder),
                'transition' => Gate::allows('transition', $workOrder),
            ],
            'allowedTransitions' => collect(
                $statusService->allowedTransitions($workOrder->status),
            )
                ->map(fn (WorkOrderStatus $status) => [
                    'value' => $status->value,
                    'label' => match ($status) {
                        WorkOrderStatus::Draft => 'Draft',
                        WorkOrderStatus::Scheduled => 'Scheduled',
                        WorkOrderStatus::InProgress => 'In Progress',
                        WorkOrderStatus::Completed => 'Completed',
                        WorkOrderStatus::Cancelled => 'Cancelled',
                    },
                ])
                ->values(),
        ]);
    }

    public function edit(WorkOrder $workOrder): Response
    {
        Gate::authorize('update', $workOrder);

        return Inertia::render('work-orders/Edit', [
            'workOrder' => $workOrder,
            'customers' => Customer::query()
                ->orderBy('name')
                ->get(['id', 'name', 'company']),
            'technicians' => User::query()
                ->where('role', UserRole::Technician)
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function update(
        UpdateWorkOrderRequest $request,
        WorkOrder $workOrder,
        WorkOrderActivityService $activityService,
    ): RedirectResponse {
        Gate::authorize('update', $workOrder);

        $validated = $request->validated();

        $workOrder->update([
            ...$validated,
            'total' => (float) $validated['subtotal']
                + (float) $validated['tax'],
        ]);

        $activityService->recordUpdated(
            $workOrder,
            $request->user(),
        );

        return to_route('work-orders.show', $workOrder)
            ->with('success', 'Work order updated successfully.');
    }

    public function transition(
        TransitionWorkOrderStatusRequest $request,
        WorkOrder $workOrder,
        WorkOrderStatusService $statusService,
        WorkOrderActivityService $activityService,
    ): RedirectResponse {
        Gate::authorize('transition', $workOrder);

        $status = WorkOrderStatus::from(
            $request->validated('status'),
        );

        $from = $workOrder->status;

        try {
            $statusService->transition($workOrder, $status);
        } catch (DomainException $exception) {
            throw ValidationException::withMessages([
                'status' => $exception->getMessage(),
            ]);
        }

        $activityService->recordStatusChanged(
            $workOrder,
            $request->user(),
            $from,
            $status,
        );

        return to_route('work-orders.show', $workOrder)
            ->with('success', 'Work order status updated successfully.');
    }
}
