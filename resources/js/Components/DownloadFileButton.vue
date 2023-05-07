<script setup>
import IconButton from './IconButton.vue'
import DownloadIcon from '@icons/download.svg'

const downloadFile = () => {
    console.log('Download file')
}
</script>

<template>
    <IconButton
        title="Download"
        @click="downloadFile"
    >
        <DownloadIcon/>
    </IconButton>
</template>
