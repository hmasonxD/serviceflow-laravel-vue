<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import {
    ArrowRight,
    ClipboardList,
    Filter,
    Plus,
    Search,
    UserRound,
} from '@lucide/vue';
import { ref } from 'vue';

import WorkOrderPriorityBadge from '@/components/work-orders/WorkOrderPriorityBadge.vue';
import WorkOrderStatusBadge from '@/components/work-orders/WorkOrderStatusBadge.vue';
import AppLayout from '@/layouts/AppLayout.vue';
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
            class="mx-auto flex w-full max-w-[1500px] flex-1 flex-col gap-7 p-6 lg:p-8"
        >
            <section
                class="from-card via-card to-muted/30 relative overflow-hidden rounded-2xl border bg-gradient-to-br p-6 shadow-sm"
            >
                <div
                    class="bg-primary/5 pointer-events-none absolute -top-24 -right-16 size-56 rounded-full blur-3xl"
                />

                <div
                    class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <p
                            class="text-primary text-xs font-semibold tracking-[0.18em] uppercase"
                        >
                            Service workflow
                        </p>

                        <h1 class="mt-2 text-3xl font-semibold tracking-tight">
                            Work Orders
                        </h1>

                        <p
                            class="text-muted-foreground mt-2 max-w-xl text-sm leading-6"
                        >
                            Coordinate service requests, technician assignments,
                            priorities, and workflow progress.
                        </p>
                    </div>

                    <Link
                        v-if="can.create"
                        href="/work-orders/create"
                        class="bg-primary text-primary-foreground inline-flex shrink-0 items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold shadow-sm transition-opacity hover:opacity-90"
                    >
                        <Plus class="size-4" />
                        Create work order
                    </Link>
                </div>
            </section>

            <section
                class="bg-card overflow-hidden rounded-2xl border shadow-sm"
            >
                <div
                    class="flex flex-col gap-4 border-b p-5 lg:p-6 xl:flex-row xl:items-end xl:justify-between"
                >
                    <div>
                        <h2 class="font-semibold">Service queue</h2>

                        <p class="text-muted-foreground mt-1 text-sm">
                            Review and filter current work orders.
                        </p>
                    </div>

                    <form
                        class="flex w-full flex-col gap-2 md:flex-row xl:w-auto"
                        @submit.prevent="submitFilters"
                    >
                        <div class="relative w-full xl:w-80">
                            <Search
                                class="text-muted-foreground absolute top-1/2 left-3 size-4 -translate-y-1/2"
                            />

                            <input
                                v-model="search"
                                type="search"
                                placeholder="Search title or customer..."
                                class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border py-2.5 pr-4 pl-10 text-sm transition outline-none focus:ring-2"
                            />
                        </div>

                        <div class="relative">
                            <Filter
                                class="text-muted-foreground pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2"
                            />

                            <select
                                v-model="status"
                                class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full appearance-none rounded-xl border py-2.5 pr-10 pl-10 text-sm transition outline-none focus:ring-2 md:w-44"
                            >
                                <option value="">All statuses</option>
                                <option value="draft">Draft</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <button
                            type="submit"
                            class="hover:bg-muted rounded-xl border px-4 py-2.5 text-sm font-medium transition-colors"
                        >
                            Apply filters
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left text-sm">
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

                                <th class="px-5 py-3 lg:px-6" />
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="workOrder in workOrders.data"
                                :key="workOrder.id"
                                class="hover:bg-muted/30 border-t transition-colors"
                            >
                                <td class="px-5 py-4 lg:px-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="bg-primary/10 text-primary flex size-9 shrink-0 items-center justify-center rounded-lg"
                                        >
                                            <ClipboardList class="size-4" />
                                        </div>

                                        <Link
                                            :href="`/work-orders/${workOrder.id}`"
                                            class="font-semibold hover:underline"
                                        >
                                            {{ workOrder.title }}
                                        </Link>
                                    </div>
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
                                    <div
                                        class="flex items-center gap-2"
                                        :class="
                                            workOrder.assignee
                                                ? 'text-foreground'
                                                : 'text-muted-foreground'
                                        "
                                    >
                                        <UserRound class="size-4 shrink-0" />

                                        <span>
                                            {{
                                                workOrder.assignee?.name ??
                                                'Unassigned'
                                            }}
                                        </span>
                                    </div>
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

                                <td class="px-5 py-4 text-right lg:px-6">
                                    <Link
                                        :href="`/work-orders/${workOrder.id}`"
                                        class="hover:bg-muted inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-sm font-medium transition-colors"
                                    >
                                        View
                                        <ArrowRight class="size-4" />
                                    </Link>
                                </td>
                            </tr>

                            <tr v-if="workOrders.data.length === 0">
                                <td
                                    colspan="6"
                                    class="text-muted-foreground px-6 py-16 text-center"
                                >
                                    <div
                                        class="bg-muted mx-auto flex size-11 items-center justify-center rounded-xl"
                                    >
                                        <ClipboardList class="size-5" />
                                    </div>

                                    <p class="text-foreground mt-4 font-medium">
                                        No work orders found
                                    </p>

                                    <p class="mt-1 text-sm">
                                        Try changing your search or status
                                        filter.
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="workOrders.links.length > 3"
                    class="flex flex-wrap items-center gap-2 border-t px-5 py-4 lg:px-6"
                >
                    <Link
                        v-for="link in workOrders.links"
                        :key="link.label"
                        :href="link.url ?? '#'"
                        v-html="link.label"
                        class="hover:bg-muted min-w-9 rounded-lg border px-3 py-2 text-center text-sm transition-colors"
                        :class="{
                            'bg-primary text-primary-foreground hover:bg-primary':
                                link.active,
                            'pointer-events-none opacity-40': !link.url,
                        }"
                    />
                </div>
            </section>
        </div>
    </AppLayout>
</template>
