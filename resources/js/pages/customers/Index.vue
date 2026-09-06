<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { index as customersIndex } from '@/routes/customers';
import type { BreadcrumbItem } from '@/types';

type Customer = {
    id: number;
    name: string;
    company: string | null;
    email: string | null;
    phone: string | null;
    city: string | null;
    province: string | null;
};

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

const props = defineProps<{
    customers: {
        data: Customer[];
        links: PaginationLink[];
    };
    filters: {
        search: string;
    };
    can: {
        create: boolean;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Customers',
        href: customersIndex().url,
    },
];

const search = ref(props.filters.search);

function submitSearch(): void {
    router.get(
        '/customers',
        {
            search: search.value,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
}
</script>

<template>
    <Head title="Customers" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="mx-auto flex w-full max-w-[1500px] flex-1 flex-col gap-6 p-6"
        >
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold">Customers</h1>

                    <p class="text-muted-foreground text-sm">
                        Manage customer records and contact information.
                    </p>
                </div>

                <Link
                    v-if="can.create"
                    href="/customers/create"
                    class="bg-primary text-primary-foreground rounded-lg px-4 py-2 text-sm"
                >
                    Add customer
                </Link>
            </div>

            <form
                class="flex flex-col gap-2 sm:flex-row"
                @submit.prevent="submitSearch"
            >
                <input
                    v-model="search"
                    type="search"
                    placeholder="Search name, company, or email..."
                    class="bg-background w-full max-w-md rounded-lg border px-3 py-2"
                />

                <button
                    type="submit"
                    class="rounded-lg border px-4 py-2 text-sm"
                >
                    Search
                </button>
            </form>

            <div class="overflow-hidden rounded-xl border">
                <table class="w-full text-left text-sm">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Company</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Phone</th>
                            <th class="px-4 py-3">Location</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="customer in customers.data"
                            :key="customer.id"
                            class="hover:bg-muted/40 border-t transition-colors"
                        >
                            <td class="px-4 py-3 font-medium">
                                {{ customer.name }}
                            </td>

                            <td class="px-4 py-3">
                                {{ customer.company ?? '—' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ customer.email ?? '—' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ customer.phone ?? '—' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ customer.city ?? '—' }}

                                <span v-if="customer.province">
                                    , {{ customer.province }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                <Link
                                    :href="`/customers/${customer.id}`"
                                    class="font-medium hover:underline"
                                >
                                    View
                                </Link>
                            </td>
                        </tr>

                        <tr v-if="customers.data.length === 0">
                            <td
                                colspan="6"
                                class="text-muted-foreground px-4 py-10 text-center"
                            >
                                No customers found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-wrap gap-2">
                <Link
                    v-for="link in customers.links"
                    :key="link.label"
                    :href="link.url ?? '#'"
                    v-html="link.label"
                    class="rounded border px-3 py-2 text-sm"
                    :class="{
                        'bg-primary text-primary-foreground': link.active,
                        'pointer-events-none opacity-50': !link.url,
                    }"
                />
            </div>
        </div>
    </AppLayout>
</template>
