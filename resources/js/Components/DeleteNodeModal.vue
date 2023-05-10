<script setup>
import { computed } from 'vue'
import ConfirmationModal from '@/Components/ConfirmationModal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'
import { capitalizeFirstLetter } from '@/lib/utils'

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    node: {
        type: Object,
        required: true,
    },
})

const type = computed(() => {
    return capitalizeFirstLetter(props.node.type)
})

const emit = defineEmits(['close', 'deleteNode'])
</script>

<template>
    <ConfirmationModal
        :show="show"
    >
        <template #title>
            Delete {{ type }}
        </template>

        <template #content>
            Are you sure you want to delete the {{ props.node.type }} "{{ props.node.name }}"?
        </template>

        <template #footer>
            <SecondaryButton
                class="mr-8"
                @click="emit('close')"
            >
                Cancel
            </SecondaryButton>
            <DangerButton
                @click="emit('deleteNode')"
            >
                Delete
            </DangerButton>
        </template>
    </ConfirmationModal>
</template>
