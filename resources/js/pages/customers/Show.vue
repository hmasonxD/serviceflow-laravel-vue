<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

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
    can: {
        update: boolean;
    };
}>();
</script>

<template>
    <Head :title="customer.name" />

    <div class="mx-auto max-w-4xl space-y-6 p-6">
        <div class="flex items-start justify-between">
            <div>
                <Link href="/customers" class="text-sm underline">
                    ← Back to customers
                </Link>

                <h1 class="mt-3 text-2xl font-semibold">
                    {{ customer.name }}
                </h1>

                <p v-if="customer.company" class="text-gray-500">
                    {{ customer.company }}
                </p>
            </div>

            <Link
                v-if="can.update"
                :href="`/customers/${customer.id}/edit`"
                class="rounded-lg border px-4 py-2 text-sm"
            >
                Edit
            </Link>
        </div>

        <div class="grid gap-6 rounded-lg border p-6 md:grid-cols-2">
            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p>{{ customer.email ?? '—' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Phone</p>
                <p>{{ customer.phone ?? '—' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Address</p>
                <p>{{ customer.address_line_1 ?? '—' }}</p>

                <p v-if="customer.address_line_2">
                    {{ customer.address_line_2 }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Location</p>

                <p>
                    {{ customer.city ?? '—' }}

                    <span v-if="customer.province">
                        , {{ customer.province }}
                    </span>

                    <span v-if="customer.postal_code">
                        {{ customer.postal_code }}
                    </span>
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Notes</p>

                <p class="whitespace-pre-line">
                    {{ customer.notes ?? '—' }}
                </p>
            </div>
        </div>
    </div>
</template>
