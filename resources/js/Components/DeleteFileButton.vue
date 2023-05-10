<script setup>
import IconButton from './IconButton.vue'
import DeleteIcon from '@icons/delete.svg'

const emit = defineEmits(['deleteNode'])
</script>

<template>
    <IconButton
        title="Delete"
        @click="emit('deleteNode')"
    >
        <DeleteIcon />
    </IconButton>
</template>
