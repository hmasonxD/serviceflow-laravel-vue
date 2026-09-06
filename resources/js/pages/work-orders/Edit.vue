<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as workOrdersIndex } from '@/routes/work-orders';
import type { BreadcrumbItem } from '@/types';
import WorkOrderForm from './partials/WorkOrderForm.vue';

const props = defineProps<{
    workOrder: {
        id: number;
        customer_id: number;
        assigned_to: number | null;
        title: string;
        description: string | null;
        priority: string;
        scheduled_at: string | null;
        subtotal: string;
        tax: string;
        notes: string | null;
    };
    customers: {
        id: number;
        name: string;
        company: string | null;
    }[];
    technicians: {
        id: number;
        name: string;
    }[];
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
    {
        title: 'Edit',
        href: '#',
    },
];
</script>

<template>
    <Head title="Edit Work Order" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto flex w-full max-w-5xl flex-1 flex-col gap-6 p-6">
            <div>
                <Link
                    :href="`/work-orders/${workOrder.id}`"
                    class="text-muted-foreground text-sm underline"
                >
                    ← Back to work order
                </Link>

                <h1 class="mt-3 text-2xl font-semibold">Edit work order</h1>

                <p class="text-muted-foreground text-sm">
                    Update {{ workOrder.title }}.
                </p>
            </div>

            <WorkOrderForm
                :action="`/work-orders/${workOrder.id}`"
                method="patch"
                submit-label="Save changes"
                :customers="customers"
                :technicians="technicians"
                :work-order="workOrder"
            />
        </div>
    </AppLayout>
</template>
