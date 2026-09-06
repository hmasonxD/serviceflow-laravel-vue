<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    priority: string;
}>();

const classes = computed(() => {
    switch (props.priority) {
        case 'urgent':
            return 'border-red-500/30 bg-red-500/10 text-red-600 dark:text-red-400';

        case 'high':
            return 'border-orange-500/30 bg-orange-500/10 text-orange-600 dark:text-orange-400';

        case 'low':
            return 'border-border bg-muted text-muted-foreground';

        default:
            return 'border-border bg-background text-foreground';
    }
});
</script>

<template>
    <span
        class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-medium capitalize"
        :class="classes"
    >
        {{ priority }}
    </span>
</template>
