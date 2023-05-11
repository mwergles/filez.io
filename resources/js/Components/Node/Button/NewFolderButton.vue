<script setup>
import { ref } from 'vue'
import useNode from '@/composables/node.composable'
import PrimaryButton from '@/Components/UI/PrimaryButton.vue'
import NewFolderModal from '@/Components/Node/Modal/NewFolderModal.vue'

const { createFolder: _createFolder } = useNode()

const emit = defineEmits(['createFolder'])
const showNewFolderModal = ref(false)

const createFolder = ({ name }) => {
    showNewFolderModal.value = false
    _createFolder({ name })
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
