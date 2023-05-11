<script setup>
import useNode from '@/composables/node.composable'
import IconButton from '@/Components/UI/IconButton.vue'
import MoveNodeUpIcon from '@icons/move-up.svg'

const { moveNode } = useNode()

const props = defineProps({
    node: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <span>
        <IconButton
            title="Move up"
            @click="moveNode({ node })"
        >
        <MoveNodeUpIcon/>
    </IconButton>
    </span>
</template>
