<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft } from '@lucide/vue';

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
        <div
            class="mx-auto flex w-full max-w-6xl flex-1 flex-col gap-6 p-6 lg:p-8"
        >
            <Link
                :href="`/customers/${customer.id}`"
                class="text-muted-foreground hover:text-foreground inline-flex w-fit items-center gap-2 text-sm font-medium transition-colors"
            >
                <ArrowLeft class="size-4" />
                Back to customer
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
                        Edit customer
                    </h1>

                    <p
                        class="text-muted-foreground mt-2 max-w-2xl text-sm leading-6"
                    >
                        Update contact information, service location, and
                        internal details for
                        <span class="text-foreground font-medium">
                            {{ customer.name }}
                        </span>
                        .
                    </p>
                </div>
            </section>

            <CustomerForm
                :action="`/customers/${customer.id}`"
                method="patch"
                :customer="customer"
                submit-label="Save changes"
            />
        </div>
    </AppLayout>
</template>
