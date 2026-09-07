<script setup lang="ts">
import { Form, Link } from '@inertiajs/vue3';
import {
    CalendarClock,
    CircleDollarSign,
    ClipboardList,
    Save,
    StickyNote,
    UserRound,
    Wrench,
} from '@lucide/vue';

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
        <section class="bg-card overflow-hidden rounded-2xl border shadow-sm">
            <div class="flex items-center gap-3 border-b px-6 py-5">
                <div
                    class="bg-primary/10 text-primary flex size-10 items-center justify-center rounded-xl"
                >
                    <ClipboardList class="size-5" />
                </div>

                <div>
                    <h2 class="font-semibold">Service details</h2>
                    <p class="text-muted-foreground text-sm">
                        Define the customer request and scope of work.
                    </p>
                </div>
            </div>

            <div class="grid gap-6 p-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Customer
                    </label>

                    <div class="relative">
                        <UserRound
                            class="text-muted-foreground pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2"
                        />

                        <select
                            name="customer_id"
                            :value="workOrder?.customer_id ?? ''"
                            class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full appearance-none rounded-xl border py-2.5 pr-4 pl-10 text-sm transition outline-none focus:ring-2"
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
                    </div>

                    <p
                        v-if="errors.customer_id"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.customer_id }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Assigned technician
                    </label>

                    <div class="relative">
                        <Wrench
                            class="text-muted-foreground pointer-events-none absolute top-1/2 left-3 size-4 -translate-y-1/2"
                        />

                        <select
                            name="assigned_to"
                            :value="workOrder?.assigned_to ?? ''"
                            class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full appearance-none rounded-xl border py-2.5 pr-4 pl-10 text-sm transition outline-none focus:ring-2"
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
                    </div>

                    <p
                        v-if="errors.assigned_to"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.assigned_to }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium">
                        Work order title
                    </label>

                    <input
                        name="title"
                        type="text"
                        :value="workOrder?.title ?? ''"
                        placeholder="e.g. Emergency boiler repair"
                        class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border px-3 py-2.5 text-sm transition outline-none focus:ring-2"
                    />

                    <p
                        v-if="errors.title"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.title }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        :value="workOrder?.description ?? ''"
                        placeholder="Describe the requested service work..."
                        class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full resize-y rounded-xl border px-3 py-3 text-sm leading-6 transition outline-none focus:ring-2"
                    />

                    <p
                        v-if="errors.description"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.description }}
                    </p>
                </div>
            </div>
        </section>

        <section class="bg-card overflow-hidden rounded-2xl border shadow-sm">
            <div class="flex items-center gap-3 border-b px-6 py-5">
                <div
                    class="bg-primary/10 text-primary flex size-10 items-center justify-center rounded-xl"
                >
                    <CalendarClock class="size-5" />
                </div>

                <div>
                    <h2 class="font-semibold">Scheduling & priority</h2>
                    <p class="text-muted-foreground text-sm">
                        Set urgency and planned service timing.
                    </p>
                </div>
            </div>

            <div class="grid gap-6 p-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Priority
                    </label>

                    <select
                        name="priority"
                        :value="workOrder?.priority ?? 'normal'"
                        class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border px-3 py-2.5 text-sm transition outline-none focus:ring-2"
                    >
                        <option value="low">Low</option>
                        <option value="normal">Normal</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>

                    <p
                        v-if="errors.priority"
                        class="text-destructive mt-1.5 text-sm"
                    >
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
                        class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border px-3 py-2.5 text-sm transition outline-none focus:ring-2"
                    />

                    <p
                        v-if="errors.scheduled_at"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.scheduled_at }}
                    </p>
                </div>
            </div>
        </section>

        <section class="bg-card overflow-hidden rounded-2xl border shadow-sm">
            <div class="flex items-center gap-3 border-b px-6 py-5">
                <div
                    class="bg-primary/10 text-primary flex size-10 items-center justify-center rounded-xl"
                >
                    <CircleDollarSign class="size-5" />
                </div>

                <div>
                    <h2 class="font-semibold">Pricing</h2>
                    <p class="text-muted-foreground text-sm">
                        Record the estimated service amount and tax.
                    </p>
                </div>
            </div>

            <div class="grid gap-6 p-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium">
                        Subtotal
                    </label>

                    <div class="relative">
                        <span
                            class="text-muted-foreground absolute top-1/2 left-3 -translate-y-1/2 text-sm"
                        >
                            $
                        </span>

                        <input
                            name="subtotal"
                            type="number"
                            step="0.01"
                            min="0"
                            :value="workOrder?.subtotal ?? 0"
                            class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border py-2.5 pr-3 pl-7 text-sm transition outline-none focus:ring-2"
                        />
                    </div>

                    <p
                        v-if="errors.subtotal"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.subtotal }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium"> Tax </label>

                    <div class="relative">
                        <span
                            class="text-muted-foreground absolute top-1/2 left-3 -translate-y-1/2 text-sm"
                        >
                            $
                        </span>

                        <input
                            name="tax"
                            type="number"
                            step="0.01"
                            min="0"
                            :value="workOrder?.tax ?? 0"
                            class="bg-background focus:border-primary/50 focus:ring-primary/20 w-full rounded-xl border py-2.5 pr-3 pl-7 text-sm transition outline-none focus:ring-2"
                        />
                    </div>

                    <p
                        v-if="errors.tax"
                        class="text-destructive mt-1.5 text-sm"
                    >
                        {{ errors.tax }}
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
                        Add context for technicians or future updates.
                    </p>
                </div>
            </div>

            <div class="p-6">
                <textarea
                    name="notes"
                    rows="5"
                    :value="workOrder?.notes ?? ''"
                    placeholder="Add internal notes..."
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
                href="/work-orders"
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
