<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    CalendarClock,
    ClipboardList,
    TriangleAlert,
    Users,
} from '@lucide/vue';

import WorkOrderPriorityBadge from '@/components/work-orders/WorkOrderPriorityBadge.vue';
import WorkOrderStatusBadge from '@/components/work-orders/WorkOrderStatusBadge.vue';
import AppLayout from '@/layouts/AppLayout.vue';
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
            class="mx-auto flex w-full max-w-[1500px] flex-1 flex-col gap-8 p-6 lg:p-8"
        >
            <section
                class="from-card via-card to-muted/30 relative overflow-hidden rounded-2xl border bg-gradient-to-br p-6 shadow-sm lg:p-8"
            >
                <div
                    class="bg-primary/5 pointer-events-none absolute -top-24 -right-20 size-64 rounded-full blur-3xl"
                />

                <div class="relative">
                    <p
                        class="text-primary text-xs font-semibold tracking-[0.18em] uppercase"
                    >
                        Service operations
                    </p>

                    <h1 class="mt-2 text-3xl font-semibold tracking-tight">
                        Dashboard
                    </h1>

                    <p
                        class="text-muted-foreground mt-2 max-w-2xl text-sm leading-6"
                    >
                        Monitor customer activity, service workload, technician
                        assignments, and work-order progress from one place.
                    </p>
                </div>
            </section>

            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div
                    class="group bg-card rounded-2xl border p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p
                                class="text-muted-foreground text-xs font-semibold tracking-wide uppercase"
                            >
                                Customers
                            </p>

                            <p
                                class="mt-3 text-3xl font-semibold tracking-tight"
                            >
                                {{ metrics.customers }}
                            </p>

                            <p class="text-muted-foreground mt-1 text-xs">
                                Active customer records
                            </p>
                        </div>

                        <div
                            class="bg-primary/10 text-primary flex size-11 items-center justify-center rounded-xl transition-transform group-hover:scale-105"
                        >
                            <Users class="size-5" />
                        </div>
                    </div>
                </div>

                <div
                    class="group bg-card rounded-2xl border p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p
                                class="text-muted-foreground text-xs font-semibold tracking-wide uppercase"
                            >
                                Open work orders
                            </p>

                            <p
                                class="mt-3 text-3xl font-semibold tracking-tight"
                            >
                                {{ metrics.openWorkOrders }}
                            </p>

                            <p class="text-muted-foreground mt-1 text-xs">
                                Awaiting completion
                            </p>
                        </div>

                        <div
                            class="bg-primary/10 text-primary flex size-11 items-center justify-center rounded-xl transition-transform group-hover:scale-105"
                        >
                            <ClipboardList class="size-5" />
                        </div>
                    </div>
                </div>

                <div
                    class="group bg-card rounded-2xl border p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p
                                class="text-muted-foreground text-xs font-semibold tracking-wide uppercase"
                            >
                                Scheduled
                            </p>

                            <p
                                class="mt-3 text-3xl font-semibold tracking-tight"
                            >
                                {{ metrics.scheduledWorkOrders }}
                            </p>

                            <p class="text-muted-foreground mt-1 text-xs">
                                Planned service visits
                            </p>
                        </div>

                        <div
                            class="bg-primary/10 text-primary flex size-11 items-center justify-center rounded-xl transition-transform group-hover:scale-105"
                        >
                            <CalendarClock class="size-5" />
                        </div>
                    </div>
                </div>

                <div
                    class="group bg-card rounded-2xl border p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p
                                class="text-muted-foreground text-xs font-semibold tracking-wide uppercase"
                            >
                                Urgent
                            </p>

                            <p
                                class="mt-3 text-3xl font-semibold tracking-tight"
                            >
                                {{ metrics.urgentWorkOrders }}
                            </p>

                            <p class="text-muted-foreground mt-1 text-xs">
                                Needs immediate attention
                            </p>
                        </div>

                        <div
                            class="bg-destructive/10 text-destructive flex size-11 items-center justify-center rounded-xl transition-transform group-hover:scale-105"
                        >
                            <TriangleAlert class="size-5" />
                        </div>
                    </div>
                </div>
            </section>

            <section
                class="grid gap-6 xl:grid-cols-[minmax(0,2fr)_minmax(300px,0.72fr)]"
            >
                <div
                    class="bg-card overflow-hidden rounded-2xl border shadow-sm"
                >
                    <div
                        class="flex items-center justify-between gap-4 border-b px-5 py-5 lg:px-6"
                    >
                        <div>
                            <h2 class="text-lg font-semibold">
                                Recent work orders
                            </h2>

                            <p class="text-muted-foreground mt-1 text-sm">
                                Latest service activity across ServiceFlow.
                            </p>
                        </div>

                        <Link
                            href="/work-orders"
                            class="hover:bg-muted inline-flex items-center gap-2 rounded-lg border px-3 py-2 text-sm font-medium transition-colors"
                        >
                            View all
                            <ArrowRight class="size-4" />
                        </Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[760px] text-left text-sm">
                            <thead class="bg-muted/40">
                                <tr>
                                    <th
                                        class="text-muted-foreground px-5 py-3 text-xs font-semibold tracking-wide uppercase lg:px-6"
                                    >
                                        Work order
                                    </th>

                                    <th
                                        class="text-muted-foreground px-4 py-3 text-xs font-semibold tracking-wide uppercase"
                                    >
                                        Customer
                                    </th>

                                    <th
                                        class="text-muted-foreground px-4 py-3 text-xs font-semibold tracking-wide uppercase"
                                    >
                                        Technician
                                    </th>

                                    <th
                                        class="text-muted-foreground px-4 py-3 text-xs font-semibold tracking-wide uppercase"
                                    >
                                        Priority
                                    </th>

                                    <th
                                        class="text-muted-foreground px-4 py-3 text-xs font-semibold tracking-wide uppercase"
                                    >
                                        Status
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="workOrder in recentWorkOrders"
                                    :key="workOrder.id"
                                    class="hover:bg-muted/30 border-t transition-colors"
                                >
                                    <td class="px-5 py-4 lg:px-6">
                                        <Link
                                            :href="`/work-orders/${workOrder.id}`"
                                            class="font-semibold hover:underline"
                                        >
                                            {{ workOrder.title }}
                                        </Link>
                                    </td>

                                    <td class="px-4 py-4">
                                        <p class="font-medium">
                                            {{ workOrder.customer.name }}
                                        </p>

                                        <p
                                            v-if="workOrder.customer.company"
                                            class="text-muted-foreground mt-0.5 text-xs"
                                        >
                                            {{ workOrder.customer.company }}
                                        </p>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span
                                            :class="
                                                workOrder.assignee
                                                    ? 'text-foreground'
                                                    : 'text-muted-foreground'
                                            "
                                        >
                                            {{
                                                workOrder.assignee?.name ??
                                                'Unassigned'
                                            }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4">
                                        <WorkOrderPriorityBadge
                                            :priority="workOrder.priority"
                                        />
                                    </td>

                                    <td class="px-4 py-4">
                                        <WorkOrderStatusBadge
                                            :status="workOrder.status"
                                        />
                                    </td>
                                </tr>

                                <tr v-if="recentWorkOrders.length === 0">
                                    <td
                                        colspan="5"
                                        class="text-muted-foreground px-6 py-14 text-center"
                                    >
                                        No work orders yet.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <aside class="bg-card rounded-2xl border p-6 shadow-sm">
                    <div>
                        <p
                            class="text-primary text-xs font-semibold tracking-wide uppercase"
                        >
                            Overview
                        </p>

                        <h2 class="mt-1 text-lg font-semibold">Work summary</h2>

                        <p class="text-muted-foreground mt-1 text-sm">
                            Current work-order distribution.
                        </p>
                    </div>

                    <div class="mt-6 divide-y">
                        <div
                            class="flex items-center justify-between gap-4 py-3 first:pt-0"
                        >
                            <WorkOrderStatusBadge status="draft" />
                            <span class="text-lg font-semibold">
                                {{ statusCounts.draft }}
                            </span>
                        </div>

                        <div
                            class="flex items-center justify-between gap-4 py-3"
                        >
                            <WorkOrderStatusBadge status="scheduled" />
                            <span class="text-lg font-semibold">
                                {{ statusCounts.scheduled }}
                            </span>
                        </div>

                        <div
                            class="flex items-center justify-between gap-4 py-3"
                        >
                            <WorkOrderStatusBadge status="in_progress" />
                            <span class="text-lg font-semibold">
                                {{ statusCounts.inProgress }}
                            </span>
                        </div>

                        <div
                            class="flex items-center justify-between gap-4 py-3"
                        >
                            <WorkOrderStatusBadge status="completed" />
                            <span class="text-lg font-semibold">
                                {{ statusCounts.completed }}
                            </span>
                        </div>

                        <div
                            class="flex items-center justify-between gap-4 py-3 pb-0"
                        >
                            <WorkOrderStatusBadge status="cancelled" />
                            <span class="text-lg font-semibold">
                                {{ statusCounts.cancelled }}
                            </span>
                        </div>
                    </div>

                    <Link
                        href="/work-orders"
                        class="bg-primary text-primary-foreground mt-7 flex w-full items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold shadow-sm transition-opacity hover:opacity-90"
                    >
                        Manage work orders
                        <ArrowRight class="size-4" />
                    </Link>
                </aside>
            </section>
        </div>
    </AppLayout>
</template>
