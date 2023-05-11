<script setup>
import DownloadFileButton from '@/Components/Node/Button/DownloadFileButton.vue'
import DeleteNodeButton from '@/Components/Node/Button/DeleteNodeButton.vue'
import RenameNodeButton from '@/Components/Node/Button/RenameNodeButton.vue'
import MoveNodeUpButton from '@/Components/Node/Button/MoveNodeUpButton.vue'

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
