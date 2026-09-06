<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as workOrdersIndex } from '@/routes/work-orders';
import type { BreadcrumbItem } from '@/types';

type Transition = {
    value: string;
    label: string;
};

type WorkOrderActivity = {
    id: number;
    type: string;
    from_status: string | null;
    to_status: string | null;
    description: string | null;
    created_at: string;
    user: {
        id: number;
        name: string;
    } | null;
};

const props = defineProps<{
    workOrder: {
        id: number;
        title: string;
        description: string | null;
        status: string;
        priority: string;
        scheduled_at: string | null;
        started_at: string | null;
        completed_at: string | null;
        subtotal: string;
        tax: string;
        total: string;
        notes: string | null;
        customer: {
            id: number;
            name: string;
            company: string | null;
        };
        assignee: {
            id: number;
            name: string;
            email: string;
        } | null;
        creator: {
            id: number;
            name: string;
        };
        activities: WorkOrderActivity[];
    };
    can: {
        update: boolean;
        transition: boolean;
    };
    allowedTransitions: Transition[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Work Orders',
        href: workOrdersIndex().url,
    },
    {
        title: props.workOrder.title,
        href: `/work-orders/${props.workOrder.id}`,
    },
];

const transitionForm = useForm({
    status: '',
});

function transitionTo(status: string): void {
    transitionForm.status = status;

    transitionForm.patch(`/work-orders/${props.workOrder.id}/status`, {
        preserveScroll: true,
    });
}

function formatStatus(value: string): string {
    return value
        .split('_')
        .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
        .join(' ');
}

function formatDate(value: string | null): string {
    if (!value) {
        return '—';
    }

    return new Date(value).toLocaleString();
}
</script>

<template>
    <Head :title="workOrder.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <Link
                        href="/work-orders"
                        class="text-muted-foreground text-sm underline"
                    >
                        ← Back to work orders
                    </Link>

                    <h1 class="mt-3 text-2xl font-semibold">
                        {{ workOrder.title }}
                    </h1>

                    <p class="text-muted-foreground text-sm">
                        {{ workOrder.customer.name }}
                    </p>
                </div>

                <Link
                    v-if="can.update"
                    :href="`/work-orders/${workOrder.id}/edit`"
                    class="rounded-lg border px-4 py-2 text-sm"
                >
                    Edit
                </Link>
            </div>

            <div class="grid gap-6 rounded-xl border p-6 md:grid-cols-2">
                <div>
                    <p class="text-muted-foreground text-sm">Status</p>
                    <p class="font-medium">
                        {{ formatStatus(workOrder.status) }}
                    </p>
                </div>

                <div>
                    <p class="text-muted-foreground text-sm">Priority</p>
                    <p class="capitalize">
                        {{ workOrder.priority }}
                    </p>
                </div>

                <div>
                    <p class="text-muted-foreground text-sm">Customer</p>
                    <p>{{ workOrder.customer.name }}</p>

                    <p
                        v-if="workOrder.customer.company"
                        class="text-muted-foreground text-sm"
                    >
                        {{ workOrder.customer.company }}
                    </p>
                </div>

                <div>
                    <p class="text-muted-foreground text-sm">
                        Assigned technician
                    </p>
                    <p>{{ workOrder.assignee?.name ?? 'Unassigned' }}</p>
                </div>

                <div>
                    <p class="text-muted-foreground text-sm">Scheduled</p>
                    <p>{{ formatDate(workOrder.scheduled_at) }}</p>
                </div>

                <div>
                    <p class="text-muted-foreground text-sm">Started</p>
                    <p>{{ formatDate(workOrder.started_at) }}</p>
                </div>

                <div>
                    <p class="text-muted-foreground text-sm">Completed</p>
                    <p>{{ formatDate(workOrder.completed_at) }}</p>
                </div>

                <div>
                    <p class="text-muted-foreground text-sm">Created by</p>
                    <p>{{ workOrder.creator.name }}</p>
                </div>

                <div>
                    <p class="text-muted-foreground text-sm">Subtotal</p>
                    <p>${{ workOrder.subtotal }}</p>
                </div>

                <div>
                    <p class="text-muted-foreground text-sm">Tax</p>
                    <p>${{ workOrder.tax }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-muted-foreground text-sm">Total</p>
                    <p class="text-lg font-semibold">${{ workOrder.total }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-muted-foreground text-sm">Description</p>
                    <p class="whitespace-pre-line">
                        {{ workOrder.description ?? '—' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-muted-foreground text-sm">Notes</p>
                    <p class="whitespace-pre-line">
                        {{ workOrder.notes ?? '—' }}
                    </p>
                </div>
            </div>

            <div
                v-if="can.transition && allowedTransitions.length > 0"
                class="rounded-xl border p-6"
            >
                <h2 class="text-lg font-semibold">Update status</h2>

                <p class="text-muted-foreground mt-1 text-sm">
                    Choose the next valid workflow state.
                </p>

                <div class="mt-4 flex flex-wrap gap-2">
                    <button
                        v-for="transition in allowedTransitions"
                        :key="transition.value"
                        type="button"
                        :disabled="transitionForm.processing"
                        class="rounded-lg border px-4 py-2 text-sm disabled:opacity-50"
                        @click="transitionTo(transition.value)"
                    >
                        {{ transition.label }}
                    </button>
                </div>

                <p
                    v-if="transitionForm.errors.status"
                    class="text-destructive mt-3 text-sm"
                >
                    {{ transitionForm.errors.status }}
                </p>
            </div>

            <div class="rounded-xl border p-6">
                <div>
                    <h2 class="text-lg font-semibold">Activity history</h2>

                    <p class="text-muted-foreground mt-1 text-sm">
                        Recorded changes to this work order.
                    </p>
                </div>

                <div
                    v-if="workOrder.activities.length > 0"
                    class="mt-6 space-y-5"
                >
                    <div
                        v-for="activity in workOrder.activities"
                        :key="activity.id"
                        class="flex gap-4"
                    >
                        <div
                            class="bg-primary mt-2 size-2 shrink-0 rounded-full"
                        />

                        <div class="min-w-0">
                            <p class="font-medium">
                                {{
                                    activity.description ??
                                    'Work order updated.'
                                }}
                            </p>

                            <p class="text-muted-foreground mt-1 text-sm">
                                {{ activity.user?.name ?? 'System' }}
                                ·
                                {{ formatDate(activity.created_at) }}
                            </p>
                        </div>
                    </div>
                </div>

                <p v-else class="text-muted-foreground mt-6 text-sm">
                    No activity recorded yet.
                </p>
            </div>
        </div>
    </AppLayout>
</template>
