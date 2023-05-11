<script setup>
import { ref, computed, watchEffect } from 'vue'
import { capitalizeFirstLetter } from '@/lib/utils'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import DialogModal from '@/Components/DialogModal.vue'

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

const emit = defineEmits(['close', 'renameNode'])
const newNodeName = ref(props.node.name)
const input = ref(null)

watchEffect(() => {
  if (input.value) {
    input.value.focus()
  }
})

const renameNode = () => {
  emit('renameNode', { newNodeName: newNodeName.value })
}
</script>

<template>
  <DialogModal
    :show="props.show"
    @close="emit('close')"
  >
    <template #title>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Rename {{ type }}
      </h2>
    </template>

    <template #content>
      <form @submit.prevent="renameNode">
        <div class="mb-4">
          <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
            New name
          </label>
          <input
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            type="text"
            v-model="newNodeName"
            :placeholder="node.name"
            ref="input"
          />
        </div>
      </form>
    </template>

    <template #footer>
      <PrimaryButton @click="renameNode">
        Rename
      </PrimaryButton>
    </template>
  </DialogModal>
</template>
