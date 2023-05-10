<script setup>
import { ref } from 'vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import NewFolderModal from '@/Components/NewFolderModal.vue'

const emit = defineEmits(['createFolder'])
const showNewFolderModal = ref(false)

const createFolder = ({ name }) => {
    showNewFolderModal.value = false
    emit('createFolder', { name })
}
</script>

<template>
    <PrimaryButton
        class="ml-6"
        @click="showNewFolderModal = true"
    >
        New Folder
    </PrimaryButton>

    <NewFolderModal
        :show="showNewFolderModal"
        @close="showNewFolderModal = false"
        @createFolder="createFolder"
    />
</template>
