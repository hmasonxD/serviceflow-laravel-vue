<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

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
}>();

const search = ref(props.filters.search);

function submitSearch(): void {
    router.get(
        '/customers',
        { search: search.value },
        {
            preserveState: true,
            replace: true,
        },
    );
}
</script>

<template>
    <Head title="Customers" />

    <div class="mx-auto max-w-7xl space-y-6 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Customers</h1>
                <p class="text-sm text-gray-500">
                    Manage customer records and contact information.
                </p>
            </div>

            <Link
                href="/customers/create"
                class="rounded-lg bg-black px-4 py-2 text-sm text-white"
            >
                Add customer
            </Link>
        </div>

        <form class="flex gap-2" @submit.prevent="submitSearch">
            <input
                v-model="search"
                type="search"
                placeholder="Search name, company, or email..."
                class="w-full max-w-md rounded-lg border px-3 py-2"
            />

            <button type="submit" class="rounded-lg border px-4 py-2 text-sm">
                Search
            </button>
        </form>

        <div class="overflow-hidden rounded-lg border">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50">
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
                        class="border-t"
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
                                class="font-medium underline"
                            >
                                View
                            </Link>
                        </td>
                    </tr>

                    <tr v-if="customers.data.length === 0">
                        <td
                            colspan="6"
                            class="px-4 py-10 text-center text-gray-500"
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
                    'bg-black text-white': link.active,
                    'pointer-events-none opacity-50': !link.url,
                }"
            />
        </div>
    </div>
</template>
