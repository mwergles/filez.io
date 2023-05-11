<script setup>
import IconButton from '@/Components/UI/IconButton.vue'
import DownloadIcon from '@icons/download.svg'

const props = defineProps({
    node: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <a
        title="Download"
        :href="node.url"
        :download="node.name"
        target="_blank"
    >
        <IconButton>
            <DownloadIcon/>
        </IconButton>
    </a>
</template>
