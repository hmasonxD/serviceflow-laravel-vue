<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    ArrowLeft,
    CalendarClock,
    CheckCircle2,
    CircleDollarSign,
    ClipboardList,
    Clock3,
    History,
    Pencil,
    UserRound,
    Wrench,
} from '@lucide/vue';

import WorkOrderPriorityBadge from '@/components/work-orders/WorkOrderPriorityBadge.vue';
import WorkOrderStatusBadge from '@/components/work-orders/WorkOrderStatusBadge.vue';
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

function formatDate(value: string | null): string {
    if (!value) {
        return '—';
    }

    return new Date(value).toLocaleString();
}

function formatMoney(value: string): string {
    return Number(value).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}
</script>

<template>
    <Head :title="workOrder.title" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="mx-auto flex w-full max-w-6xl flex-1 flex-col gap-6 p-6 lg:p-8"
        >
            <Link
                href="/work-orders"
                class="text-muted-foreground hover:text-foreground inline-flex w-fit items-center gap-2 text-sm font-medium transition-colors"
            >
                <ArrowLeft class="size-4" />
                Back to work orders
            </Link>

            <section
                class="from-card via-card to-muted/30 relative overflow-hidden rounded-2xl border bg-gradient-to-br p-6 shadow-sm lg:p-8"
            >
                <div
                    class="bg-primary/5 pointer-events-none absolute -top-24 -right-20 size-64 rounded-full blur-3xl"
                />

                <div
                    class="relative flex flex-col gap-6 md:flex-row md:items-start md:justify-between"
                >
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <WorkOrderStatusBadge :status="workOrder.status" />

                            <WorkOrderPriorityBadge
                                :priority="workOrder.priority"
                            />
                        </div>

                        <h1
                            class="mt-4 text-3xl font-semibold tracking-tight lg:text-4xl"
                        >
                            {{ workOrder.title }}
                        </h1>

                        <div
                            class="text-muted-foreground mt-3 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm"
                        >
                            <span class="inline-flex items-center gap-2">
                                <UserRound class="size-4" />
                                {{ workOrder.customer.name }}
                            </span>

                            <span
                                v-if="workOrder.assignee"
                                class="inline-flex items-center gap-2"
                            >
                                <Wrench class="size-4" />
                                {{ workOrder.assignee.name }}
                            </span>
                        </div>
                    </div>

                    <Link
                        v-if="can.update"
                        :href="`/work-orders/${workOrder.id}/edit`"
                        class="hover:bg-muted inline-flex shrink-0 items-center justify-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-semibold transition-colors"
                    >
                        <Pencil class="size-4" />
                        Edit work order
                    </Link>
                </div>
            </section>

            <div
                class="grid gap-6 lg:grid-cols-[minmax(0,1.45fr)_minmax(300px,0.7fr)]"
            >
                <div class="space-y-6">
                    <section class="bg-card rounded-2xl border shadow-sm">
                        <div class="border-b px-6 py-5">
                            <h2 class="text-lg font-semibold">
                                Work order details
                            </h2>

                            <p class="text-muted-foreground mt-1 text-sm">
                                Service information and assignment details.
                            </p>
                        </div>

                        <div class="grid gap-0 md:grid-cols-2">
                            <div class="border-b p-5 md:border-r">
                                <p
                                    class="text-muted-foreground text-xs font-semibold tracking-wide uppercase"
                                >
                                    Customer
                                </p>

                                <p class="mt-2 font-semibold">
                                    {{ workOrder.customer.name }}
                                </p>

                                <p
                                    v-if="workOrder.customer.company"
                                    class="text-muted-foreground mt-0.5 text-sm"
                                >
                                    {{ workOrder.customer.company }}
                                </p>
                            </div>

                            <div class="border-b p-5">
                                <p
                                    class="text-muted-foreground text-xs font-semibold tracking-wide uppercase"
                                >
                                    Technician
                                </p>

                                <p class="mt-2 font-semibold">
                                    {{
                                        workOrder.assignee?.name ?? 'Unassigned'
                                    }}
                                </p>

                                <p
                                    v-if="workOrder.assignee"
                                    class="text-muted-foreground mt-0.5 text-sm"
                                >
                                    {{ workOrder.assignee.email }}
                                </p>
                            </div>

                            <div class="border-b p-5 md:border-r">
                                <div class="flex items-center gap-2">
                                    <CalendarClock
                                        class="text-muted-foreground size-4"
                                    />

                                    <p
                                        class="text-muted-foreground text-xs font-semibold tracking-wide uppercase"
                                    >
                                        Scheduled
                                    </p>
                                </div>

                                <p class="mt-2 font-medium">
                                    {{ formatDate(workOrder.scheduled_at) }}
                                </p>
                            </div>

                            <div class="border-b p-5">
                                <div class="flex items-center gap-2">
                                    <Clock3
                                        class="text-muted-foreground size-4"
                                    />

                                    <p
                                        class="text-muted-foreground text-xs font-semibold tracking-wide uppercase"
                                    >
                                        Started
                                    </p>
                                </div>

                                <p class="mt-2 font-medium">
                                    {{ formatDate(workOrder.started_at) }}
                                </p>
                            </div>

                            <div class="border-b p-5 md:border-r md:border-b-0">
                                <div class="flex items-center gap-2">
                                    <CheckCircle2
                                        class="text-muted-foreground size-4"
                                    />

                                    <p
                                        class="text-muted-foreground text-xs font-semibold tracking-wide uppercase"
                                    >
                                        Completed
                                    </p>
                                </div>

                                <p class="mt-2 font-medium">
                                    {{ formatDate(workOrder.completed_at) }}
                                </p>
                            </div>

                            <div class="p-5">
                                <p
                                    class="text-muted-foreground text-xs font-semibold tracking-wide uppercase"
                                >
                                    Created by
                                </p>

                                <p class="mt-2 font-medium">
                                    {{ workOrder.creator.name }}
                                </p>
                            </div>
                        </div>
                    </section>

                    <section class="bg-card rounded-2xl border p-6 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div
                                class="bg-primary/10 text-primary flex size-10 items-center justify-center rounded-xl"
                            >
                                <ClipboardList class="size-5" />
                            </div>

                            <div>
                                <h2 class="font-semibold">
                                    Service description
                                </h2>

                                <p class="text-muted-foreground text-sm">
                                    Scope and internal service notes.
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-6">
                            <div>
                                <p
                                    class="text-muted-foreground text-xs font-semibold tracking-wide uppercase"
                                >
                                    Description
                                </p>

                                <p
                                    class="mt-2 text-sm leading-6 whitespace-pre-line"
                                >
                                    {{ workOrder.description ?? '—' }}
                                </p>
                            </div>

                            <div class="border-t pt-5">
                                <p
                                    class="text-muted-foreground text-xs font-semibold tracking-wide uppercase"
                                >
                                    Notes
                                </p>

                                <p
                                    class="mt-2 text-sm leading-6 whitespace-pre-line"
                                >
                                    {{ workOrder.notes ?? '—' }}
                                </p>
                            </div>
                        </div>
                    </section>

                    <section class="bg-card rounded-2xl border shadow-sm">
                        <div class="flex items-center gap-3 border-b px-6 py-5">
                            <div
                                class="bg-primary/10 text-primary flex size-10 items-center justify-center rounded-xl"
                            >
                                <History class="size-5" />
                            </div>

                            <div>
                                <h2 class="font-semibold">Activity history</h2>

                                <p class="text-muted-foreground text-sm">
                                    Recorded changes to this work order.
                                </p>
                            </div>
                        </div>

                        <div
                            v-if="workOrder.activities.length > 0"
                            class="px-6 py-2"
                        >
                            <div
                                v-for="(
                                    activity, index
                                ) in workOrder.activities"
                                :key="activity.id"
                                class="relative flex gap-4 py-5"
                            >
                                <div
                                    v-if="
                                        index !==
                                        workOrder.activities.length - 1
                                    "
                                    class="bg-border absolute top-8 bottom-0 left-[5px] w-px"
                                />

                                <div
                                    class="bg-primary ring-background relative z-10 mt-1.5 size-2.5 shrink-0 rounded-full ring-4"
                                />

                                <div class="min-w-0">
                                    <p class="font-medium">
                                        {{
                                            activity.description ??
                                            'Work order updated.'
                                        }}
                                    </p>

                                    <p
                                        class="text-muted-foreground mt-1.5 text-sm"
                                    >
                                        {{ activity.user?.name ?? 'System' }}
                                        ·
                                        {{ formatDate(activity.created_at) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <p
                            v-else
                            class="text-muted-foreground px-6 py-10 text-center text-sm"
                        >
                            No activity recorded yet.
                        </p>
                    </section>
                </div>

                <aside class="space-y-6">
                    <section class="bg-card rounded-2xl border p-6 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div
                                class="bg-primary/10 text-primary flex size-10 items-center justify-center rounded-xl"
                            >
                                <CircleDollarSign class="size-5" />
                            </div>

                            <div>
                                <h2 class="font-semibold">Financial summary</h2>

                                <p class="text-muted-foreground text-sm">
                                    Work-order totals.
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div
                                class="text-muted-foreground flex items-center justify-between text-sm"
                            >
                                <span>Subtotal</span>

                                <span class="text-foreground font-medium">
                                    ${{ formatMoney(workOrder.subtotal) }}
                                </span>
                            </div>

                            <div
                                class="text-muted-foreground flex items-center justify-between text-sm"
                            >
                                <span>Tax</span>

                                <span class="text-foreground font-medium">
                                    ${{ formatMoney(workOrder.tax) }}
                                </span>
                            </div>

                            <div
                                class="flex items-end justify-between border-t pt-4"
                            >
                                <span class="font-semibold">Total</span>

                                <span
                                    class="text-2xl font-semibold tracking-tight"
                                >
                                    ${{ formatMoney(workOrder.total) }}
                                </span>
                            </div>
                        </div>
                    </section>

                    <section
                        v-if="can.transition && allowedTransitions.length > 0"
                        class="bg-card rounded-2xl border p-6 shadow-sm"
                    >
                        <p
                            class="text-primary text-xs font-semibold tracking-wide uppercase"
                        >
                            Workflow
                        </p>

                        <h2 class="mt-1 text-lg font-semibold">
                            Update status
                        </h2>

                        <p class="text-muted-foreground mt-1 text-sm">
                            Choose the next valid state in the service workflow.
                        </p>

                        <div class="mt-5 grid gap-2">
                            <button
                                v-for="transition in allowedTransitions"
                                :key="transition.value"
                                type="button"
                                :disabled="transitionForm.processing"
                                class="hover:bg-muted flex w-full items-center justify-between rounded-xl border px-4 py-3 text-left text-sm font-medium transition-colors disabled:cursor-not-allowed disabled:opacity-50"
                                @click="transitionTo(transition.value)"
                            >
                                {{ transition.label }}

                                <span aria-hidden="true">→</span>
                            </button>
                        </div>

                        <p
                            v-if="transitionForm.errors.status"
                            class="text-destructive mt-4 text-sm"
                        >
                            {{ transitionForm.errors.status }}
                        </p>
                    </section>
                </aside>
            </div>
        </div>
    </AppLayout>
</template>
