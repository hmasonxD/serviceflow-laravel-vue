<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as workOrdersIndex } from '@/routes/work-orders';
import type { BreadcrumbItem } from '@/types';
import WorkOrderForm from './partials/WorkOrderForm.vue';

defineProps<{
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
        title: 'Create',
        href: '#',
    },
];
</script>

<template>
    <Head title="Create Work Order" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <div>
                <Link
                    href="/work-orders"
                    class="text-muted-foreground text-sm underline"
                >
                    ← Back to work orders
                </Link>

                <h1 class="mt-3 text-2xl font-semibold">Create work order</h1>

                <p class="text-muted-foreground text-sm">
                    Create and assign a new service job.
                </p>
            </div>

            <WorkOrderForm
                action="/work-orders"
                method="post"
                submit-label="Create work order"
                :customers="customers"
                :technicians="technicians"
            />
        </div>
    </AppLayout>
</template>
