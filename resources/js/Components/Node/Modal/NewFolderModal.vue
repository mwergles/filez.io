<script setup>
import { ref } from 'vue'
import PrimaryButton from '@/Components/UI/PrimaryButton.vue'
import DialogModal from '@/Components/Modal/DialogModal.vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close', 'createFolder']);
const folderName = ref('');

const createFolder = () => {
    emit('createFolder', { name: folderName.value });
    folderName.value = '';
};
</script>

<template>
    <DialogModal
        :show="show"
        @close="$emit('close')"
    >
        <template #title>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                New Folder
            </h2>
        </template>

        <template #content>
            <form @submit.prevent="createFolder">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                        Folder Name
                    </label>
                    <input
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        type="text"
                        v-model="folderName"
                        placeholder="Folder Name"
                    />
                </div>
            </form>
        </template>

        <template #footer>
            <PrimaryButton @click="createFolder">
                Create
            </PrimaryButton>
        </template>
    </DialogModal>
</template>
