<script setup>
import IconButton from './IconButton.vue'
import MoveNodeUpIcon from '@icons/move-up.svg'

const props = defineProps({
    node: {
        type: Object,
        required: true,
    },
})

const emit = defineEmits(['moveNode'])
</script>

<template>
    <span>
        <IconButton
            title="Move up"
            @click="emit('moveNode')"
        >
        <MoveNodeUpIcon/>
    </IconButton>
    </span>
</template>
