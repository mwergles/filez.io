<script setup>
import IconButton from './IconButton.vue'
import DownloadIcon from '@icons/edit.svg'

const renameNode = () => {
    console.log('Rename node')
}
</script>

<template>
    <IconButton
        title="Rename"
        @click="renameNode"
    >
        <DownloadIcon/>
    </IconButton>
</template>
