<script setup lang="ts">
import { Form } from '@inertiajs/vue3';

type CustomerOption = {
    id: number;
    name: string;
    company: string | null;
};

type TechnicianOption = {
    id: number;
    name: string;
};

type WorkOrderFormData = {
    customer_id?: number | null;
    assigned_to?: number | null;
    title?: string | null;
    description?: string | null;
    priority?: string | null;
    scheduled_at?: string | null;
    subtotal?: string | number | null;
    tax?: string | number | null;
    notes?: string | null;
};

defineProps<{
    action: string;
    method: 'post' | 'patch';
    submitLabel: string;
    customers: CustomerOption[];
    technicians: TechnicianOption[];
    workOrder?: WorkOrderFormData;
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
                <label class="mb-2 block text-sm font-medium"> Customer </label>

                <select
                    name="customer_id"
                    :value="workOrder?.customer_id ?? ''"
                    class="bg-background w-full rounded-lg border px-3 py-2"
                >
                    <option value="">Select customer</option>

                    <option
                        v-for="customer in customers"
                        :key="customer.id"
                        :value="customer.id"
                    >
                        {{ customer.name }}
                        <template v-if="customer.company">
                            - {{ customer.company }}
                        </template>
                    </option>
                </select>

                <p v-if="errors.customer_id" class="mt-1 text-sm text-red-600">
                    {{ errors.customer_id }}
                </p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">
                    Assigned technician
                </label>

                <select
                    name="assigned_to"
                    :value="workOrder?.assigned_to ?? ''"
                    class="w-full rounded-lg border px-3 py-2"
                >
                    <option value="">Unassigned</option>

                    <option
                        v-for="technician in technicians"
                        :key="technician.id"
                        :value="technician.id"
                    >
                        {{ technician.name }}
                    </option>
                </select>

                <p v-if="errors.assigned_to" class="mt-1 text-sm text-red-600">
                    {{ errors.assigned_to }}
                </p>
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium"> Title </label>

                <input
                    name="title"
                    type="text"
                    :value="workOrder?.title ?? ''"
                    class="w-full rounded-lg border px-3 py-2"
                />

                <p v-if="errors.title" class="mt-1 text-sm text-red-600">
                    {{ errors.title }}
                </p>
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium">
                    Description
                </label>

                <textarea
                    name="description"
                    rows="4"
                    :value="workOrder?.description ?? ''"
                    class="w-full rounded-lg border px-3 py-2"
                />

                <p v-if="errors.description" class="mt-1 text-sm text-red-600">
                    {{ errors.description }}
                </p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium"> Priority </label>

                <select
                    name="priority"
                    :value="workOrder?.priority ?? 'normal'"
                    class="w-full rounded-lg border px-3 py-2"
                >
                    <option value="low">Low</option>
                    <option value="normal">Normal</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>

                <p v-if="errors.priority" class="mt-1 text-sm text-red-600">
                    {{ errors.priority }}
                </p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium">
                    Scheduled at
                </label>

                <input
                    name="scheduled_at"
                    type="datetime-local"
                    :value="workOrder?.scheduled_at ?? ''"
                    class="w-full rounded-lg border px-3 py-2"
                />

                <p v-if="errors.scheduled_at" class="mt-1 text-sm text-red-600">
                    {{ errors.scheduled_at }}
                </p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium"> Subtotal </label>

                <input
                    name="subtotal"
                    type="number"
                    step="0.01"
                    min="0"
                    :value="workOrder?.subtotal ?? 0"
                    class="w-full rounded-lg border px-3 py-2"
                />

                <p v-if="errors.subtotal" class="mt-1 text-sm text-red-600">
                    {{ errors.subtotal }}
                </p>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium"> Tax </label>

                <input
                    name="tax"
                    type="number"
                    step="0.01"
                    min="0"
                    :value="workOrder?.tax ?? 0"
                    class="w-full rounded-lg border px-3 py-2"
                />

                <p v-if="errors.tax" class="mt-1 text-sm text-red-600">
                    {{ errors.tax }}
                </p>
            </div>

            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium"> Notes </label>

                <textarea
                    name="notes"
                    rows="4"
                    :value="workOrder?.notes ?? ''"
                    class="w-full rounded-lg border px-3 py-2"
                />

                <p v-if="errors.notes" class="mt-1 text-sm text-red-600">
                    {{ errors.notes }}
                </p>
            </div>
        </div>

        <div class="flex justify-end">
            <button
                type="submit"
                :disabled="processing"
                class="rounded-lg bg-black px-4 py-2 text-white disabled:opacity-50"
            >
                {{ processing ? 'Saving...' : submitLabel }}
            </button>
        </div>
    </Form>
</template>
