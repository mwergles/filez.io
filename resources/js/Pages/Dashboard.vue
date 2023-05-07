<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import FileList from '@/Components/FileList.vue';
import UploadButton from '@/Components/UploadButton.vue'
import NewFolderButton from '@/Components/NewFolderButton.vue'
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Filez.io
            </h2>

            <section class="mt-10">
                <UploadButton />
                <NewFolderButton />
            </section>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <FileList />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
