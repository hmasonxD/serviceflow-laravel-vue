<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import CustomerForm from './partials/CustomerForm.vue';
</script>

<template>
    <Head title="Create Customer" />

    <div class="mx-auto max-w-4xl space-y-6 p-6">
        <div>
            <Link href="/customers" class="text-sm underline">
                ← Back to customers
            </Link>

            <h1 class="mt-3 text-2xl font-semibold">Create customer</h1>
            <p class="text-sm text-gray-500">Add a customer to ServiceFlow.</p>
        </div>

        <CustomerForm
            action="/customers"
            method="post"
            submit-label="Create customer"
        />
    </div>
</template>
