<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as customersIndex } from '@/routes/customers';
import type { BreadcrumbItem } from '@/types';
import CustomerForm from './partials/CustomerForm.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Customers',
        href: customersIndex().url,
    },
    {
        title: 'Create',
        href: '#',
    },
];
</script>

<template>
    <Head title="Create Customer" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <div>
                <Link
                    href="/customers"
                    class="text-muted-foreground text-sm underline"
                >
                    ← Back to customers
                </Link>

                <h1 class="mt-3 text-2xl font-semibold">Create customer</h1>

                <p class="text-muted-foreground text-sm">
                    Add a customer to ServiceFlow.
                </p>
            </div>

            <CustomerForm
                action="/customers"
                method="post"
                submit-label="Create customer"
            />
        </div>
    </AppLayout>
</template>
