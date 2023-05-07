<script setup>
import IconButton from './IconButton.vue'
import DeleteIcon from '@icons/delete.svg'

const deleteFile = () => {
    console.log('Delete node')
}
</script>

<template>
    <IconButton
        title="Delete"
        @click="deleteFile"
    >
        <DeleteIcon />
    </IconButton>
</template>
