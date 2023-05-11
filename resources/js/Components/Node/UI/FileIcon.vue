<script setup>
import GenericFileIcon from '@icons/file-color.svg'
import PdfFileIcon from '@icons/pdf.svg'
import TxtFileIcon from '@icons/txt.svg'
import XlsFileIcon from '@icons/xls.svg'
import DocFileIcon from '@icons/doc.svg'
import PptFileIcon from '@icons/ppt.svg'

const fileTypesMapping = {
    'application/pdf': PdfFileIcon,
    'text/plain': TxtFileIcon,
    'application/vnd.ms-excel': XlsFileIcon,
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': XlsFileIcon,
    'application/msword': DocFileIcon,
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document': DocFileIcon,
    'application/vnd.ms-powerpoint': PptFileIcon,
    'application/vnd.openxmlformats-officedocument.presentationml.presentation': PptFileIcon,
}

const props = defineProps({
    node: {
        type: Object,
        required: true,
    },
})
</script>

<template>
    <Component
        v-if="node.type === 'file'"
        :is="fileTypesMapping[node.mime_type] ?? GenericFileIcon"
        class="h-8 w-8"
    />
</template>
