<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    CalendarClock,
    ClipboardList,
    TriangleAlert,
    Users,
} from '@lucide/vue';

import AppLayout from '@/layouts/AppLayout.vue';
import WorkOrderPriorityBadge from '@/components/work-orders/WorkOrderPriorityBadge.vue';
import WorkOrderStatusBadge from '@/components/work-orders/WorkOrderStatusBadge.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

type WorkOrder = {
    id: number;
    title: string;
    status: string;
    priority: string;
    customer: {
        id: number;
        name: string;
        company: string | null;
    };
    assignee: {
        id: number;
        name: string;
    } | null;
};

defineProps<{
    metrics: {
        customers: number;
        openWorkOrders: number;
        scheduledWorkOrders: number;
        urgentWorkOrders: number;
    };

    statusCounts: {
        draft: number;
        scheduled: number;
        inProgress: number;
        completed: number;
        cancelled: number;
    };

    recentWorkOrders: WorkOrder[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="mx-auto flex w-full max-w-[1500px] flex-1 flex-col gap-6 p-6"
        >
            <div>
                <h1 class="text-2xl font-semibold">Dashboard</h1>

                <p class="text-muted-foreground text-sm">
                    Monitor customers, work orders, and service activity.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="bg-card rounded-xl border p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-muted-foreground text-sm">
                                Customers
                            </p>

                            <p
                                class="mt-2 text-3xl font-semibold tracking-tight"
                            >
                                {{ metrics.customers }}
                            </p>
                        </div>

                        <div
                            class="bg-muted flex size-10 items-center justify-center rounded-lg"
                        >
                            <Users class="size-5" />
                        </div>
                    </div>
                </div>

                <div class="bg-card rounded-xl border p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-muted-foreground text-sm">
                                Open work orders
                            </p>

                            <p
                                class="mt-2 text-3xl font-semibold tracking-tight"
                            >
                                {{ metrics.openWorkOrders }}
                            </p>
                        </div>

                        <div
                            class="bg-muted flex size-10 items-center justify-center rounded-lg"
                        >
                            <ClipboardList class="size-5" />
                        </div>
                    </div>
                </div>

                <div class="bg-card rounded-xl border p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-muted-foreground text-sm">
                                Scheduled
                            </p>

                            <p
                                class="mt-2 text-3xl font-semibold tracking-tight"
                            >
                                {{ metrics.scheduledWorkOrders }}
                            </p>
                        </div>

                        <div
                            class="bg-muted flex size-10 items-center justify-center rounded-lg"
                        >
                            <CalendarClock class="size-5" />
                        </div>
                    </div>
                </div>

                <div class="bg-card rounded-xl border p-5 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-muted-foreground text-sm">Urgent</p>

                            <p
                                class="mt-2 text-3xl font-semibold tracking-tight"
                            >
                                {{ metrics.urgentWorkOrders }}
                            </p>
                        </div>

                        <div
                            class="bg-destructive/10 text-destructive flex size-10 items-center justify-center rounded-lg"
                        >
                            <TriangleAlert class="size-5" />
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(280px,0.7fr)]"
            >
                <section class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold">
                                Recent work orders
                            </h2>

                            <p class="text-muted-foreground text-sm">
                                Latest activity across ServiceFlow.
                            </p>
                        </div>

                        <Link
                            href="/work-orders"
                            class="hover:bg-muted rounded-lg border px-3 py-2 text-sm font-medium transition-colors"
                        >
                            View all
                        </Link>
                    </div>

                    <div class="bg-card overflow-hidden rounded-xl border">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-muted/50">
                                <tr>
                                    <th class="px-4 py-3">Work order</th>

                                    <th class="px-4 py-3">Customer</th>

                                    <th class="px-4 py-3">Technician</th>

                                    <th class="px-4 py-3">Priority</th>

                                    <th class="px-4 py-3">Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="workOrder in recentWorkOrders"
                                    :key="workOrder.id"
                                    class="hover:bg-muted/40 border-t transition-colors"
                                >
                                    <td class="px-4 py-3">
                                        <Link
                                            :href="`/work-orders/${workOrder.id}`"
                                            class="font-medium hover:underline"
                                        >
                                            {{ workOrder.title }}
                                        </Link>
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ workOrder.customer.name }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{
                                            workOrder.assignee?.name ??
                                            'Unassigned'
                                        }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <WorkOrderPriorityBadge
                                            :priority="workOrder.priority"
                                        />
                                    </td>

                                    <td class="px-4 py-3">
                                        <WorkOrderStatusBadge
                                            :status="workOrder.status"
                                        />
                                    </td>
                                </tr>

                                <tr v-if="recentWorkOrders.length === 0">
                                    <td
                                        colspan="5"
                                        class="text-muted-foreground px-4 py-10 text-center"
                                    >
                                        No work orders yet.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <aside class="bg-card rounded-xl border p-5">
                    <div>
                        <h2 class="text-lg font-semibold">Work summary</h2>

                        <p class="text-muted-foreground text-sm">
                            Current work-order distribution.
                        </p>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <WorkOrderStatusBadge status="draft" />

                            <span class="font-semibold">
                                {{ statusCounts.draft }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <WorkOrderStatusBadge status="scheduled" />

                            <span class="font-semibold">
                                {{ statusCounts.scheduled }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <WorkOrderStatusBadge status="in_progress" />

                            <span class="font-semibold">
                                {{ statusCounts.inProgress }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <WorkOrderStatusBadge status="completed" />

                            <span class="font-semibold">
                                {{ statusCounts.completed }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <WorkOrderStatusBadge status="cancelled" />

                            <span class="font-semibold">
                                {{ statusCounts.cancelled }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 border-t pt-5">
                        <Link
                            href="/work-orders"
                            class="bg-primary text-primary-foreground flex w-full items-center justify-center rounded-lg px-4 py-2 text-sm font-medium"
                        >
                            Manage work orders
                        </Link>
                    </div>
                </aside>
            </div>
        </div>
    </AppLayout>
</template>
