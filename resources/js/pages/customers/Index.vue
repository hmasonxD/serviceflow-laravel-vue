<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import {
    ArrowRight,
    Building2,
    Mail,
    MapPin,
    Plus,
    Search,
    UserRound,
} from '@lucide/vue';
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

function formatLocation(customer: Customer): string {
    return [customer.city, customer.province].filter(Boolean).join(', ') || '—';
}
</script>

<template>
    <Head title="Customers" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="mx-auto flex w-full max-w-[1500px] flex-1 flex-col gap-7 p-6 lg:p-8"
        >
            <section
                class="from-card via-card to-muted/30 relative overflow-hidden rounded-2xl border bg-gradient-to-br p-6 shadow-sm"
            >
                <div
                    class="bg-primary/5 pointer-events-none absolute -top-24 -right-16 size-56 rounded-full blur-3xl"
                />

                <div
                    class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div>
                        <p
                            class="text-primary text-xs font-semibold tracking-[0.18em] uppercase"
                        >
                            Customer management
                        </p>

                        <h1 class="mt-2 text-3xl font-semibold tracking-tight">
                            Customers
                        </h1>

                        <p
                            class="text-muted-foreground mt-2 max-w-xl text-sm leading-6"
                        >
                            Maintain customer records, company details, and
                            service contact information.
                        </p>
                    </div>

                    <Link
                        v-if="can.create"
                        href="/customers/create"
                        class="bg-primary text-primary-foreground inline-flex shrink-0 items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold shadow-sm transition-opacity hover:opacity-90"
                    >
                        <Plus class="size-4" />
                        Add customer
                    </Link>
                </div>
            </section>

            <section
                class="bg-card overflow-hidden rounded-2xl border shadow-sm"
            >
                <div
                    class="flex flex-col gap-4 border-b p-5 lg:flex-row lg:items-center lg:justify-between lg:p-6"
                >
                    <div>
                        <h2 class="font-semibold">Customer directory</h2>

                        <p class="text-muted-foreground mt-1 text-sm">
                            Search and review customer records.
                        </p>
                    </div>

                    <form
                        class="flex w-full flex-col gap-2 sm:flex-row lg:w-auto"
                        @submit.prevent="submitSearch"
                    >
                        <div class="relative w-full lg:w-96">
                            <Search
                                class="text-muted-foreground absolute top-1/2 left-3 size-4 -translate-y-1/2"
                            />

                            <input
                                v-model="search"
                                type="search"
                                placeholder="Search name, company, or email..."
                                class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border py-2.5 pr-4 pl-10 text-sm transition outline-none focus:ring-2"
                            />
                        </div>

                        <button
                            type="submit"
                            class="hover:bg-muted rounded-xl border px-4 py-2.5 text-sm font-medium transition-colors"
                        >
                            Search
                        </button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left text-sm">
                        <thead class="bg-muted/40">
                            <tr>
                                <th
                                    class="text-muted-foreground px-5 py-3 text-xs font-semibold tracking-wide uppercase lg:px-6"
                                >
                                    Customer
                                </th>

                                <th
                                    class="text-muted-foreground px-4 py-3 text-xs font-semibold tracking-wide uppercase"
                                >
                                    Company
                                </th>

                                <th
                                    class="text-muted-foreground px-4 py-3 text-xs font-semibold tracking-wide uppercase"
                                >
                                    Email
                                </th>

                                <th
                                    class="text-muted-foreground px-4 py-3 text-xs font-semibold tracking-wide uppercase"
                                >
                                    Phone
                                </th>

                                <th
                                    class="text-muted-foreground px-4 py-3 text-xs font-semibold tracking-wide uppercase"
                                >
                                    Location
                                </th>

                                <th class="px-5 py-3 lg:px-6" />
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="customer in customers.data"
                                :key="customer.id"
                                class="hover:bg-muted/30 border-t transition-colors"
                            >
                                <td class="px-5 py-4 lg:px-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="bg-primary/10 text-primary flex size-9 shrink-0 items-center justify-center rounded-lg"
                                        >
                                            <UserRound class="size-4" />
                                        </div>

                                        <Link
                                            :href="`/customers/${customer.id}`"
                                            class="font-semibold hover:underline"
                                        >
                                            {{ customer.name }}
                                        </Link>
                                    </div>
                                </td>

                                <td class="px-4 py-4">
                                    <div
                                        v-if="customer.company"
                                        class="flex items-center gap-2"
                                    >
                                        <Building2
                                            class="text-muted-foreground size-4 shrink-0"
                                        />

                                        <span>{{ customer.company }}</span>
                                    </div>

                                    <span v-else class="text-muted-foreground">
                                        —
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <div
                                        v-if="customer.email"
                                        class="flex items-center gap-2"
                                    >
                                        <Mail
                                            class="text-muted-foreground size-4 shrink-0"
                                        />

                                        <span>{{ customer.email }}</span>
                                    </div>

                                    <span v-else class="text-muted-foreground">
                                        —
                                    </span>
                                </td>

                                <td class="text-muted-foreground px-4 py-4">
                                    {{ customer.phone ?? '—' }}
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <MapPin
                                            class="text-muted-foreground size-4 shrink-0"
                                        />

                                        <span>{{
                                            formatLocation(customer)
                                        }}</span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-right lg:px-6">
                                    <Link
                                        :href="`/customers/${customer.id}`"
                                        class="hover:bg-muted inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-sm font-medium transition-colors"
                                    >
                                        View
                                        <ArrowRight class="size-4" />
                                    </Link>
                                </td>
                            </tr>

                            <tr v-if="customers.data.length === 0">
                                <td
                                    colspan="6"
                                    class="text-muted-foreground px-6 py-16 text-center"
                                >
                                    <div
                                        class="bg-muted mx-auto flex size-11 items-center justify-center rounded-xl"
                                    >
                                        <UserRound class="size-5" />
                                    </div>

                                    <p class="text-foreground mt-4 font-medium">
                                        No customers found
                                    </p>

                                    <p class="mt-1 text-sm">
                                        Try changing your search criteria.
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="customers.links.length > 3"
                    class="flex flex-wrap items-center gap-2 border-t px-5 py-4 lg:px-6"
                >
                    <Link
                        v-for="link in customers.links"
                        :key="link.label"
                        :href="link.url ?? '#'"
                        v-html="link.label"
                        class="hover:bg-muted min-w-9 rounded-lg border px-3 py-2 text-center text-sm transition-colors"
                        :class="{
                            'bg-primary text-primary-foreground hover:bg-primary':
                                link.active,
                            'pointer-events-none opacity-40': !link.url,
                        }"
                    />
                </div>
            </section>
        </div>
    </AppLayout>
</template>
