<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import CustomerForm from './partials/CustomerForm.vue';

defineProps<{
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
</script>

<template>
    <Head title="Edit Customer" />

    <div class="mx-auto max-w-4xl space-y-6 p-6">
        <div>
            <Link :href="`/customers/${customer.id}`" class="text-sm underline">
                ← Back to customer
            </Link>

            <h1 class="mt-3 text-2xl font-semibold">Edit customer</h1>
        </div>

        <CustomerForm
            :action="`/customers/${customer.id}`"
            method="patch"
            :customer="customer"
            submit-label="Save changes"
        />
    </div>
</template>
