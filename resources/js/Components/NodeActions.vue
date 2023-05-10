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

const emit = defineEmits(['updated', 'moveNode'])

async function renameNode ({ newNodeName }) {
    await axios.patch(`/api/node/${props.node.id}`, { name: newNodeName })
    emit('updated')
}

async function deleteNode () {
    await axios.delete(`/api/node/${props.node.id}`)
    emit('updated')
}
</script>

<template>
    <DownloadFileButton
        v-if="node.type === 'file'"
        :node="node"
    />
    <RenameNodeButton
        class="ml-6"
        :node="node"
        @renameNode="renameNode"
    />
    <MoveNodeUpButton
        v-if="node.parent_id"
        class="ml-6"
        :node="node"
        @moveNode="emit('moveNode')"
    />
    <DeleteNodeButton
        v-if="node.type === 'file' || node.length === 0"
        class="ml-6"
        :node="node"
        @deleteNode="deleteNode"
    />
</template>
