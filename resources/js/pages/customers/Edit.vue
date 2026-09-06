<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as customersIndex } from '@/routes/customers';
import type { BreadcrumbItem } from '@/types';
import CustomerForm from './partials/CustomerForm.vue';

const props = defineProps<{
    customer: {
        id: number;
        name: string;
        company: string | null;
        email: string | null;
        phone: string | null;
        address_line_1: string | null;
        address_line_2: string | null;
        city: string | null;
        province: string | null;
        postal_code: string | null;
        notes: string | null;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Customers',
        href: customersIndex().url,
    },
    {
        title: props.customer.name,
        href: `/customers/${props.customer.id}`,
    },
    {
        title: 'Edit',
        href: '#',
    },
];
</script>

<template>
    <Head title="Edit Customer" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto flex w-full max-w-5xl flex-1 flex-col gap-6 p-6">
            <div>
                <Link
                    :href="`/customers/${customer.id}`"
                    class="text-muted-foreground text-sm underline"
                >
                    ← Back to customer
                </Link>

                <h1 class="mt-3 text-2xl font-semibold">Edit customer</h1>

                <p class="text-muted-foreground text-sm">
                    Update {{ customer.name }}.
                </p>
            </div>

            <CustomerForm
                :action="`/customers/${customer.id}`"
                method="patch"
                :customer="customer"
                submit-label="Save changes"
            />
        </div>
    </AppLayout>
</template>
