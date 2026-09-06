<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\WorkOrder;

class WorkOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, WorkOrder $workOrder): bool
    {
        return $user->role === UserRole::Admin
            || $workOrder->assigned_to === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, WorkOrder $workOrder): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function transition(User $user, WorkOrder $workOrder): bool
    {
        return $user->role === UserRole::Admin
            || $workOrder->assigned_to === $user->id;
    }
}
