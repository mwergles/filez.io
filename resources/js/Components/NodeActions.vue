<script setup>
import DownloadFileButton from '@/Components/DownloadFileButton.vue'
import DeleteNodeButton from '@/Components/DeleteNodeButton.vue'
import RenameNodeButton from '@/Components/RenameNodeButton.vue'
import MoveNodeUpButton from '@/Components/MoveNodeUpButton.vue'

const props = defineProps({
    node: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <DownloadFileButton
        v-if="node.type === 'file'"
        :node="node"
    />
    <RenameNodeButton
        class="ml-6"
        :node="node"
    />
    <MoveNodeUpButton
        v-if="node.parent_id"
        class="ml-6"
        :node="node"
    />
    <DeleteNodeButton
        v-if="node.type === 'file' || node.length === 0"
        class="ml-6"
        :node="node"
    />
</template>
