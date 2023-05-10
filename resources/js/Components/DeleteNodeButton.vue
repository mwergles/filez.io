<script setup>
import { ref } from 'vue'
import IconButton from './IconButton.vue'
import DeleteIcon from '@icons/delete.svg'
import DeleteNodeModal from '@/Components/DeleteNodeModal.vue'

const props = defineProps({
    node: {
        type: Object,
        required: true,
    },
})

const showDeleteNodeModal = ref(false)
const emit = defineEmits(['deleteNode'])
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
        :node="props.node"
        :show="showDeleteNodeModal"
        @close="showDeleteNodeModal = false"
        @deleteNode="emit('deleteNode')"
    />
    </span>
</template>
