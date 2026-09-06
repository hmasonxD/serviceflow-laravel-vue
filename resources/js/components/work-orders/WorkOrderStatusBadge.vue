<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    status: string;
}>();

const label = computed(() =>
    props.status
        .split('_')
        .map((part) => part.charAt(0).toUpperCase() + part.slice(1))
        .join(' '),
);

const classes = computed(() => {
    switch (props.status) {
        case 'scheduled':
            return 'border-blue-500/30 bg-blue-500/10 text-blue-600 dark:text-blue-400';

        case 'in_progress':
            return 'border-amber-500/30 bg-amber-500/10 text-amber-600 dark:text-amber-400';

        case 'completed':
            return 'border-emerald-500/30 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400';

        case 'cancelled':
            return 'border-red-500/30 bg-red-500/10 text-red-600 dark:text-red-400';

        default:
            return 'border-border bg-muted text-muted-foreground';
    }
});
</script>

<template>
    <span
        class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-medium"
        :class="classes"
    >
        {{ label }}
    </span>
</template>
