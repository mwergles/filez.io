<script setup>
import { ref } from 'vue'
import useNode from '@/composables/node'
import PrimaryButton from './PrimaryButton.vue'

const { uploadFile: _uploadFile } = useNode()
const fileInput = ref(null)

const selectFile = () => {
    fileInput.value.click()
}

const uploadFile = async () => {
    await _uploadFile({ file: fileInput.value.files[0] })
    fileInput.value.value = ''
}
</script>

<template>
    <input
        type="file"
        ref="fileInput"
        accept=".pdf,.doc,.docx.xls,.xlsx,.ppt,.pptx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation"
        hidden
        @change="uploadFile"
    />
    <PrimaryButton @click="selectFile">
        Upload
    </PrimaryButton>
</template>
