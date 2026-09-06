<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as customersIndex } from '@/routes/customers';
import type { BreadcrumbItem } from '@/types';

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
    can: {
        update: boolean;
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
];
</script>

<template>
    <Head :title="customer.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto flex w-full max-w-5xl flex-1 flex-col gap-6 p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <Link
                        href="/customers"
                        class="text-muted-foreground text-sm underline"
                    >
                        ← Back to customers
                    </Link>

                    <h1 class="mt-3 text-2xl font-semibold">
                        {{ customer.name }}
                    </h1>

                    <p
                        v-if="customer.company"
                        class="text-muted-foreground text-sm"
                    >
                        {{ customer.company }}
                    </p>
                </div>

                <Link
                    v-if="can.update"
                    :href="`/customers/${customer.id}/edit`"
                    class="hover:bg-muted rounded-lg border px-4 py-2 text-sm transition-colors"
                >
                    Edit
                </Link>
            </div>

            <div
                class="bg-card grid gap-6 rounded-xl border p-6 md:grid-cols-2"
            >
                <div>
                    <p class="text-muted-foreground text-sm">Email</p>
                    <p>{{ customer.email ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-muted-foreground text-sm">Phone</p>
                    <p>{{ customer.phone ?? '—' }}</p>
                </div>

                <div>
                    <p class="text-muted-foreground text-sm">Address</p>
                    <p>{{ customer.address_line_1 ?? '—' }}</p>

                    <p v-if="customer.address_line_2">
                        {{ customer.address_line_2 }}
                    </p>
                </div>

                <div>
                    <p class="text-muted-foreground text-sm">Location</p>

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
                    <p class="text-muted-foreground text-sm">Notes</p>

                    <p class="whitespace-pre-line">
                        {{ customer.notes ?? '—' }}
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
