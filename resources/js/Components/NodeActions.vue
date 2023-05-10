<script setup>
import DownloadFileButton from '@/Components/DownloadFileButton.vue'
import DeleteFileButton from '@/Components/DeleteFileButton.vue'
import RenameNodeButton from '@/Components/RenameNodeButton.vue'

const props = defineProps({
    node: {
        type: Object,
        required: true,
    },
})

const emit = defineEmits(['updated'])

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
    <DeleteFileButton
        class="ml-6"
        v-if="node.type === 'file' || node.length === 0"
        @deleteNode="deleteNode"
    />
</template>
