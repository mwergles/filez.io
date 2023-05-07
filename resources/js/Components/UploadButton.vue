<script setup>
import { ref } from 'vue'
import PrimaryButton from './PrimaryButton.vue'

const fileInput = ref(null)

const selectFile = () => {
    fileInput.value.click()
}

const uploadFiles = () => {
    console.log(fileInput.value.files)
}
</script>

<template>
    <input
        type="file"
        ref="fileInput"
        accept=".pdf,.doc,.docx.xls,.xlsx,.ppt,.pptx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation"
        multiple
        hidden
        @change="uploadFiles"
    />
    <PrimaryButton @click="selectFile">
        Upload
    </PrimaryButton>
</template>
