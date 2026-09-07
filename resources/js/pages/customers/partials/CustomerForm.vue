<script setup lang="ts">
import { Form, Link } from '@inertiajs/vue3';
import {
    Building2,
    ContactRound,
    Mail,
    MapPin,
    Save,
    StickyNote,
    UserRound,
} from '@lucide/vue';

type CustomerFormData = {
    name?: string | null;
    company?: string | null;
    email?: string | null;
    phone?: string | null;
    address_line_1?: string | null;
    address_line_2?: string | null;
    city?: string | null;
    province?: string | null;
    postal_code?: string | null;
    notes?: string | null;
};

defineProps<{
    action: string;
    method: 'post' | 'patch';
    submitLabel: string;
    customer?: CustomerFormData;
}>();
</script>

<template>
    <Form
        :action="action"
        :method="method"
        class="space-y-6"
        v-slot="{ errors, processing }"
    >
        <section class="bg-card overflow-hidden rounded-2xl border shadow-sm">
            <div class="flex items-center gap-3 border-b px-6 py-5">
                <div
                    class="bg-primary/10 text-primary flex size-10 items-center justify-center rounded-xl"
                >
                    <ContactRound class="size-5" />
                </div>

                <div>
                    <h2 class="font-semibold">Customer details</h2>
                    <p class="text-muted-foreground text-sm">
                        Basic identity and contact information.
                    </p>
                </div>
            </div>

            <div class="grid gap-6 p-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Customer name
                    </label>

                    <div class="relative">
                        <UserRound
                            class="text-muted-foreground absolute top-1/2 left-3 size-4 -translate-y-1/2"
                        />

                        <input
                            name="name"
                            type="text"
                            :value="customer?.name ?? ''"
                            placeholder="Enter customer name"
                            class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border py-2.5 pr-4 pl-10 text-sm transition outline-none focus:ring-2"
                        />
                    </div>

                    <p
                        v-if="errors.name"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.name }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Company
                    </label>

                    <div class="relative">
                        <Building2
                            class="text-muted-foreground absolute top-1/2 left-3 size-4 -translate-y-1/2"
                        />

                        <input
                            name="company"
                            type="text"
                            :value="customer?.company ?? ''"
                            placeholder="Optional company name"
                            class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border py-2.5 pr-4 pl-10 text-sm transition outline-none focus:ring-2"
                        />
                    </div>

                    <p
                        v-if="errors.company"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.company }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Email
                    </label>

                    <div class="relative">
                        <Mail
                            class="text-muted-foreground absolute top-1/2 left-3 size-4 -translate-y-1/2"
                        />

                        <input
                            name="email"
                            type="email"
                            :value="customer?.email ?? ''"
                            placeholder="customer@example.com"
                            class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border py-2.5 pr-4 pl-10 text-sm transition outline-none focus:ring-2"
                        />
                    </div>

                    <p
                        v-if="errors.email"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.email }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Phone
                    </label>

                    <input
                        name="phone"
                        type="text"
                        :value="customer?.phone ?? ''"
                        placeholder="Phone number"
                        class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border px-3 py-2.5 text-sm transition outline-none focus:ring-2"
                    />

                    <p
                        v-if="errors.phone"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.phone }}
                    </p>
                </div>
            </div>
        </section>

        <section class="bg-card overflow-hidden rounded-2xl border shadow-sm">
            <div class="flex items-center gap-3 border-b px-6 py-5">
                <div
                    class="bg-primary/10 text-primary flex size-10 items-center justify-center rounded-xl"
                >
                    <MapPin class="size-5" />
                </div>

                <div>
                    <h2 class="font-semibold">Address</h2>
                    <p class="text-muted-foreground text-sm">
                        Service location and mailing details.
                    </p>
                </div>
            </div>

            <div class="grid gap-6 p-6 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium">
                        Address line 1
                    </label>

                    <input
                        name="address_line_1"
                        type="text"
                        :value="customer?.address_line_1 ?? ''"
                        placeholder="Street address"
                        class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border px-3 py-2.5 text-sm transition outline-none focus:ring-2"
                    />

                    <p
                        v-if="errors.address_line_1"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.address_line_1 }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium">
                        Address line 2
                    </label>

                    <input
                        name="address_line_2"
                        type="text"
                        :value="customer?.address_line_2 ?? ''"
                        placeholder="Unit, suite, building, etc."
                        class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border px-3 py-2.5 text-sm transition outline-none focus:ring-2"
                    />

                    <p
                        v-if="errors.address_line_2"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.address_line_2 }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium"> City </label>

                    <input
                        name="city"
                        type="text"
                        :value="customer?.city ?? ''"
                        placeholder="City"
                        class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border px-3 py-2.5 text-sm transition outline-none focus:ring-2"
                    />

                    <p
                        v-if="errors.city"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.city }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Province
                    </label>

                    <input
                        name="province"
                        type="text"
                        :value="customer?.province ?? ''"
                        placeholder="Province"
                        class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border px-3 py-2.5 text-sm transition outline-none focus:ring-2"
                    />

                    <p
                        v-if="errors.province"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.province }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Postal code
                    </label>

                    <input
                        name="postal_code"
                        type="text"
                        :value="customer?.postal_code ?? ''"
                        placeholder="Postal code"
                        class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border px-3 py-2.5 text-sm transition outline-none focus:ring-2"
                    />

                    <p
                        v-if="errors.postal_code"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.postal_code }}
                    </p>
                </div>
            </div>
        </section>

        <section class="bg-card overflow-hidden rounded-2xl border shadow-sm">
            <div class="flex items-center gap-3 border-b px-6 py-5">
                <div
                    class="bg-primary/10 text-primary flex size-10 items-center justify-center rounded-xl"
                >
                    <StickyNote class="size-5" />
                </div>

                <div>
                    <h2 class="font-semibold">Internal notes</h2>
                    <p class="text-muted-foreground text-sm">
                        Optional context for future service work.
                    </p>
                </div>
            </div>

            <div class="p-6">
                <textarea
                    name="notes"
                    rows="5"
                    :value="customer?.notes ?? ''"
                    placeholder="Add notes about this customer..."
                    class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full resize-y rounded-xl border px-3 py-3 text-sm leading-6 transition outline-none focus:ring-2"
                />

                <p v-if="errors.notes" class="text-destructive mt-1.5 text-sm">
                    {{ errors.notes }}
                </p>
            </div>
        </section>

        <div
            class="bg-card flex flex-col-reverse gap-3 rounded-2xl border p-4 shadow-sm sm:flex-row sm:items-center sm:justify-end"
        >
            <Link
                href="/customers"
                class="hover:bg-muted inline-flex items-center justify-center rounded-xl border px-4 py-2.5 text-sm font-medium transition-colors"
            >
                Cancel
            </Link>

            <button
                type="submit"
                :disabled="processing"
                class="bg-primary text-primary-foreground inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold shadow-sm transition-opacity hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50"
            >
                <Save class="size-4" />
                {{ processing ? 'Saving...' : submitLabel }}
            </button>
        </div>
    </Form>
</template>
