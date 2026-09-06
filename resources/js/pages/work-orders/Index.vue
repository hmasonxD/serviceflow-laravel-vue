<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import WorkOrderPriorityBadge from '@/components/work-orders/WorkOrderPriorityBadge.vue';
import WorkOrderStatusBadge from '@/components/work-orders/WorkOrderStatusBadge.vue';
import { index as workOrdersIndex } from '@/routes/work-orders';
import type { BreadcrumbItem } from '@/types';

type WorkOrder = {
    id: number;
    title: string;
    status: string;
    priority: string;
    scheduled_at: string | null;
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

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

const props = defineProps<{
    workOrders: {
        data: WorkOrder[];
        links: PaginationLink[];
    };
    filters: {
        search: string;
        status: string;
    };
    can: {
        create: boolean;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Work Orders',
        href: workOrdersIndex().url,
    },
];

const search = ref(props.filters.search);
const status = ref(props.filters.status);

function submitFilters(): void {
    router.get(
        '/work-orders',
        {
            search: search.value,
            status: status.value,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
}
</script>

<template>
    <Head title="Work Orders" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="mx-auto flex w-full max-w-[1500px] flex-1 flex-col gap-6 p-6"
        >
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold">Work Orders</h1>

                    <p class="text-muted-foreground text-sm">
                        Manage scheduled and active service work.
                    </p>
                </div>

                <Link
                    v-if="can.create"
                    href="/work-orders/create"
                    class="bg-primary text-primary-foreground rounded-lg px-4 py-2 text-sm"
                >
                    Create work order
                </Link>
            </div>

            <form
                class="flex flex-col gap-3 md:flex-row"
                @submit.prevent="submitFilters"
            >
                <input
                    v-model="search"
                    type="search"
                    placeholder="Search title or customer..."
                    class="bg-background w-full rounded-lg border px-3 py-2 md:max-w-md"
                />

                <select
                    v-model="status"
                    class="bg-background rounded-lg border px-3 py-2"
                >
                    <option value="">All statuses</option>
                    <option value="draft">Draft</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <button
                    type="submit"
                    class="hover:bg-muted rounded-lg border px-4 py-2 text-sm transition-colors"
                >
                    Filter
                </button>
            </form>

            <div class="bg-card overflow-hidden rounded-xl border">
                <table class="w-full text-left text-sm">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Customer</th>
                            <th class="px-4 py-3">Technician</th>
                            <th class="px-4 py-3">Priority</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="workOrder in workOrders.data"
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
                                {{ workOrder.assignee?.name ?? 'Unassigned' }}
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

                            <td class="px-4 py-3 text-right">
                                <Link
                                    :href="`/work-orders/${workOrder.id}`"
                                    class="font-medium hover:underline"
                                >
                                    View
                                </Link>
                            </td>
                        </tr>

                        <tr v-if="workOrders.data.length === 0">
                            <td
                                colspan="6"
                                class="text-muted-foreground px-4 py-10 text-center"
                            >
                                No work orders found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-wrap gap-2">
                <Link
                    v-for="link in workOrders.links"
                    :key="link.label"
                    :href="link.url ?? '#'"
                    v-html="link.label"
                    class="rounded border px-3 py-2 text-sm"
                    :class="{
                        'bg-primary text-primary-foreground': link.active,
                        'pointer-events-none opacity-50': !link.url,
                    }"
                />
            </div>
        </div>
    </AppLayout>
</template>
