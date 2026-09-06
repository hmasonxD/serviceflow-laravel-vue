<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
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
    recentWorkOrders: WorkOrder[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

function formatStatus(value: string): string {
    return value
        .split('_')
        .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
        .join(' ');
}
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <div>
                <h1 class="text-2xl font-semibold">Dashboard</h1>

                <p class="text-muted-foreground text-sm">
                    Overview of your ServiceFlow operations.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border p-5">
                    <p class="text-muted-foreground text-sm">Customers</p>

                    <p class="mt-2 text-3xl font-semibold">
                        {{ metrics.customers }}
                    </p>
                </div>

                <div class="rounded-xl border p-5">
                    <p class="text-muted-foreground text-sm">
                        Open work orders
                    </p>

                    <p class="mt-2 text-3xl font-semibold">
                        {{ metrics.openWorkOrders }}
                    </p>
                </div>

                <div class="rounded-xl border p-5">
                    <p class="text-muted-foreground text-sm">Scheduled</p>

                    <p class="mt-2 text-3xl font-semibold">
                        {{ metrics.scheduledWorkOrders }}
                    </p>
                </div>

                <div class="rounded-xl border p-5">
                    <p class="text-muted-foreground text-sm">Urgent</p>

                    <p class="mt-2 text-3xl font-semibold">
                        {{ metrics.urgentWorkOrders }}
                    </p>
                </div>
            </div>

            <div class="space-y-4">
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
                        class="text-sm font-medium underline"
                    >
                        View all
                    </Link>
                </div>

                <div class="overflow-hidden rounded-xl border">
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
                                class="border-t"
                            >
                                <td class="px-4 py-3">
                                    <Link
                                        :href="`/work-orders/${workOrder.id}`"
                                        class="font-medium underline"
                                    >
                                        {{ workOrder.title }}
                                    </Link>
                                </td>

                                <td class="px-4 py-3">
                                    {{ workOrder.customer.name }}
                                </td>

                                <td class="px-4 py-3">
                                    {{
                                        workOrder.assignee?.name ?? 'Unassigned'
                                    }}
                                </td>

                                <td class="px-4 py-3 capitalize">
                                    {{ workOrder.priority }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ formatStatus(workOrder.status) }}
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
            </div>
        </div>
    </AppLayout>
</template>
