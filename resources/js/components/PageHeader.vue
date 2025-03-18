<template>
    <div class="pt-6" v-bind="$attrs">
        <div
            class="header flex h-16 items-center justify-between gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12 md:px-4"
        >
            <div>
                <h1 class="text-lg font-semibold">{{ title }}</h1>
                <p v-if="subtitle" class="text-sm text-gray-500">{{ subtitle }}</p>
            </div>
            <slot name="actions"></slot>
        </div>
        <slot></slot>
    </div>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    subtitle: {
        type: String,
        default: "",
    },
});
</script>
