<script setup lang="ts">
import { Form } from '@inertiajs/vue3';

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
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-medium"> Name </label>

                <input
                    name="name"
                    type="text"
                    :value="customer?.name ?? ''"
                    class="bg-background w-full rounded-lg border px-3 py-2"
                />

                <p v-if="errors.name" class="text-destructive mt-1 text-sm">
                    {{ errors.name }}
                </p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium"> Company </label>

                <input
                    name="company"
                    type="text"
                    :value="customer?.company ?? ''"
                    class="bg-background w-full rounded-lg border px-3 py-2"
                />

                <p v-if="errors.company" class="text-destructive mt-1 text-sm">
                    {{ errors.company }}
                </p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium"> Email </label>

                <input
                    name="email"
                    type="email"
                    :value="customer?.email ?? ''"
                    class="bg-background w-full rounded-lg border px-3 py-2"
                />

                <p v-if="errors.email" class="text-destructive mt-1 text-sm">
                    {{ errors.email }}
                </p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium"> Phone </label>

                <input
                    name="phone"
                    type="text"
                    :value="customer?.phone ?? ''"
                    class="bg-background w-full rounded-lg border px-3 py-2"
                />

                <p v-if="errors.phone" class="text-destructive mt-1 text-sm">
                    {{ errors.phone }}
                </p>
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium">
                    Address line 1
                </label>

                <input
                    name="address_line_1"
                    type="text"
                    :value="customer?.address_line_1 ?? ''"
                    class="bg-background w-full rounded-lg border px-3 py-2"
                />

                <p
                    v-if="errors.address_line_1"
                    class="text-destructive mt-1 text-sm"
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
                    class="bg-background w-full rounded-lg border px-3 py-2"
                />

                <p
                    v-if="errors.address_line_2"
                    class="text-destructive mt-1 text-sm"
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
                    class="bg-background w-full rounded-lg border px-3 py-2"
                />

                <p v-if="errors.city" class="text-destructive mt-1 text-sm">
                    {{ errors.city }}
                </p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium"> Province </label>

                <input
                    name="province"
                    type="text"
                    :value="customer?.province ?? ''"
                    class="bg-background w-full rounded-lg border px-3 py-2"
                />

                <p v-if="errors.province" class="text-destructive mt-1 text-sm">
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
                    class="bg-background w-full rounded-lg border px-3 py-2"
                />

                <p
                    v-if="errors.postal_code"
                    class="text-destructive mt-1 text-sm"
                >
                    {{ errors.postal_code }}
                </p>
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium"> Notes </label>

                <textarea
                    name="notes"
                    rows="5"
                    :value="customer?.notes ?? ''"
                    class="bg-background w-full rounded-lg border px-3 py-2"
                />

                <p v-if="errors.notes" class="text-destructive mt-1 text-sm">
                    {{ errors.notes }}
                </p>
            </div>
        </div>

        <div class="flex justify-end">
            <button
                type="submit"
                :disabled="processing"
                class="bg-primary text-primary-foreground rounded-lg px-4 py-2 disabled:opacity-50"
            >
                {{ processing ? 'Saving...' : submitLabel }}
            </button>
        </div>
    </Form>
</template>
