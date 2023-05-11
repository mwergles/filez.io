<script setup>
const props = defineProps({
    node: {
        type: Object,
        required: true,
    },
})

const emit = defineEmits(['navigateToNode'])
</script>

<template>
    <a
        href="#"
        @click.prevent="emit('navigateToNode', node)"
    >
        {{ node.name }}
    </a>
</template>
