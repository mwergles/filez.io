<script setup>
import { ref } from 'vue'
import useNode from '@/composables/node'
import IconButton from './IconButton.vue'
import DeleteIcon from '@icons/delete.svg'
import DeleteNodeModal from '@/Components/DeleteNodeModal.vue'

const { deleteNode } = useNode()

const props = defineProps({
    node: {
        type: Object,
        required: true,
    },
})

const showDeleteNodeModal = ref(false)
</script>

<template>
    <span>
        <IconButton
            title="Delete"
            @click="showDeleteNodeModal = true"
        >
        <DeleteIcon/>
    </IconButton>

    <DeleteNodeModal
        :node="node"
        :show="showDeleteNodeModal"
        @close="showDeleteNodeModal = false"
        @deleteNode="deleteNode({ node })"
    />
    </span>
</template>
