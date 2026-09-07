<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from '@lucide/vue';

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
        <div
            class="mx-auto flex w-full max-w-6xl flex-1 flex-col gap-6 p-6 lg:p-8"
        >
            <Link
                href="/customers"
                class="text-muted-foreground hover:text-foreground inline-flex w-fit items-center gap-2 text-sm font-medium transition-colors"
            >
                <ArrowLeft class="size-4" />
                Back to customers
            </Link>

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
                        Customer management
                    </p>

                    <h1 class="mt-2 text-3xl font-semibold tracking-tight">
                        Create customer
                    </h1>

                    <p
                        class="text-muted-foreground mt-2 max-w-2xl text-sm leading-6"
                    >
                        Add a new customer profile with contact information,
                        service location, and internal notes.
                    </p>
                </div>
            </section>

            <CustomerForm
                action="/customers"
                method="post"
                submit-label="Create customer"
            />
        </div>
    </AppLayout>
</template>
