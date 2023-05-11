<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import NodeList from '@/Components/NodeList.vue';
import UploadButton from '@/Components/UploadButton.vue'
import NewFolderButton from '@/Components/NewFolderButton.vue'
import Breadcumb from '@/Components/Breadcumb.vue'
import EmptyDirectory from '@/Components/EmptyDirectory.vue'
import useNode from '@/composables/node'
import useNavigation from '@/composables/navigation'

useNavigation()

const {
    nodes,
    path,
    moveNode,
} = useNode()
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <Breadcumb />
            </h2>

            <section class="mt-10">
                <UploadButton />
                <NewFolderButton />
            </section>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <NodeList
                        v-if="nodes.length > 0"
                        @moveNode="moveNode"
                    />
                    <EmptyDirectory v-else />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
