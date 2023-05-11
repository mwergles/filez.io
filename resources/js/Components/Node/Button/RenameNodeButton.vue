<script setup>
import { ref } from 'vue'
import useNode from '@/composables/node.composable'
import EditIcon from '@icons/edit.svg'
import IconButton from '@/Components/UI/IconButton.vue'
import RenameNodeModal from '@/Components/Node/Modal/RenameNodeModal.vue'

const { renameNode: _renameNode } = useNode()

const showRenameNodeModal = ref(false)

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

    _renameNode({ node: props.node, newNodeName })
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
            :node="node"
            @close="showRenameNodeModal = false"
            @renameNode="renameNode"
        />
    </span>
</template>
