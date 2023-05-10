<script setup>
import { onMounted, ref } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue';
import NodeList from '@/Components/NodeList.vue';
import UploadButton from '@/Components/UploadButton.vue'
import NewFolderButton from '@/Components/NewFolderButton.vue'
import nodeApi from '@/api/node'

const currentNode = ref(null)
const nodes = ref([])

onMounted(async () => {
    await fetchNodes()
})

async function fetchNodes (node = null) {
    nodes.value = await nodeApi.getNodes({ node })
}

async function navigateToNode (node) {
    currentNode.value = node
    await fetchNodes(node)
}

async function moveNode({ node, target }) {
    await nodeApi.moveNode({ node, target })
    await fetchNodes(target ? currentNode.value : node.parent_id)
}

async function createFolder ({ name }) {
    const targetId = currentNode.value?.id ?? null

    await nodeApi.createFolder({ name, targetId })
    await fetchNodes(currentNode.value)
}

async function uploadFile ({ file }) {
    const targetId = currentNode.value?.id ?? null

    await nodeApi.uploadFile({ file, targetId })
    await fetchNodes(currentNode.value)
}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Filez.io
                <span v-if="currentNode">
                    / {{ currentNode.name }}
                </span>
            </h2>

            <section class="mt-10">
                <UploadButton @upload="uploadFile" />
                <NewFolderButton @createFolder="createFolder" />
            </section>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <NodeList
                        v-if="nodes.length > 0"
                        :nodes="nodes"
                        @openFolder="navigateToNode"
                        @moveNode="moveNode"
                    />

                    <div v-else class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <p class="text-gray-500 dark:text-gray-400">
                            You don't have any files yet.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
