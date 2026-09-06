<script setup lang="ts">
import { Workflow } from '@lucide/vue';
</script>

<template>
    <div
        class="bg-primary text-primary-foreground flex aspect-square size-8 items-center justify-center rounded-lg"
    >
        <Workflow class="size-5" />
    </div>

    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="truncate font-semibold"> ServiceFlow </span>

        <span class="text-muted-foreground truncate text-xs">
            Service Operations
        </span>
    </div>
</template>
