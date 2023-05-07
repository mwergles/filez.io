<script setup>
import { defineProps } from 'vue'
import DownloadFileButton from '@/Components/DownloadFileButton.vue'
import DeleteFileButton from '@/Components/DeleteFileButton.vue'
import RenameNodeButton from '@/Components/RenameNodeButton.vue'

const props = defineProps({
    node: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <DownloadFileButton v-if="node.type === 'file'" />
    <RenameNodeButton class="ml-6" />
    <DeleteFileButton
        class="ml-6"
        v-if="node.type === 'file' || node.length === 0"
    />
</template>
