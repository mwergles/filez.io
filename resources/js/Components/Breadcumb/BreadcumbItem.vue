<script setup>
const props = defineProps({
    node: {
        type: Object,
        required: true,
    },
})

</script>

<template>
    <span>
        {{ node.name }}
    </span>
</template>
