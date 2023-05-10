<script setup>
import { ref } from 'vue'
import IconButton from './IconButton.vue'
import EditIcon from '@icons/edit.svg'
import RenameNodeModal from '@/Components/RenameNodeModal.vue'

const showRenameNodeModal = ref(false)

const emit = defineEmits(['renameNode'])

const props = defineProps({
    node: {
        type: Object,
        required: true,
    },
})

const renameNode = ({ newNodeName }) => {
    showRenameNodeModal.value = false

    if (!newNodeName || newNodeName === props.node.name) {
        return
    }

    emit('renameNode', { newNodeName })
}
</script>

<template>
    <span>
        <IconButton
            title="Rename"
            @click="showRenameNodeModal = true"
        >
            <EditIcon/>
        </IconButton>

        <RenameNodeModal
            :show="showRenameNodeModal"
            :node="props.node"
            @close="showRenameNodeModal = false"
            @renameNode="renameNode"
        />
    </span>
</template>
