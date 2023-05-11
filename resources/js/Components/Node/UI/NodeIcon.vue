<script setup>
import FolderIcon from '@icons/folder.svg'
import FileIcon from '@/Components/Node/UI/FileIcon.vue'

const props = defineProps({
    node: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <FolderIcon
        class="h-8 inline-block"
        v-if="node.type === 'folder'"
    />
    <FileIcon
        class="h-8 inline-block"
        v-if="node.type === 'file'"
        :node="node"
    />
</template>
