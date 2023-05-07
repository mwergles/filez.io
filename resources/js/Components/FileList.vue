<script setup>
import NodeIcon from '@/Components/NodeIcon.vue'
import NodeActions from '@/Components/NodeActions.vue'

const nodes = [
    {
        id: 1,
        name: 'Foo',
        type: 'folder',
        lastModified: '',
        size: '',
        length: 3,
    },
    {
        id: 2,
        name: 'Bar',
        type: 'folder',
        lastModified: '',
        size: '',
        length: 2,
    },
    {
        id: 3,
        name: 'Baz',
        type: 'folder',
        lastModified: '',
        size: '',
        length: 0,
    },
    {
        id: 4,
        name: 'Foo',
        type: 'file',
        lastModified: '2021-08-01',
        size: '1.2 MB',
    },
    {
        id: 5,
        name: 'Bar',
        type: 'file',
        lastModified: '2021-08-01',
        size: '1.2 MB',
    },
    {
        id: 6,
        name: 'Baz',
        type: 'file',
        lastModified: '2021-08-01',
        size: '1.2 MB',
    },
    {
        id: 7,
        name: 'Foo',
        type: 'file',
        lastModified: '2021-08-01',
        size: '1.2 MB',
    },
    {
        id: 8,
        name: 'Bar',
        type: 'file',
        lastModified: '2021-08-01',
        size: '1.2 MB',
    },
    {
        id: 9,
        name: 'Baz',
        type: 'file',
        lastModified: '2021-08-01',
        size: '1.2 MB',
    },
]

let nodeBeingMoved = null

const openFolder = ({ node }) => {
    if (node.type !== 'folder' || node.length === 0) {
        return
    }

    console.log('Open folder', node)
}

const onDrop = ({ node }) => {
    console.log('Drop', node, nodeBeingMoved)
}

const onDragOver = ({ node, $event }) => {
    if (node.type !== 'folder') {
        return
    }

    $event.preventDefault()
    $event.dataTransfer.dropEffect = 'move'
}

const onDragStart = ({ node }) => {
    nodeBeingMoved = node
}

const onDragEnd = () => {
    nodeBeingMoved = null
}
</script>

<template>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full text-center text-sm font-light">
                        <thead
                            class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900">
                        <tr>
                            <th scope="col" class="pl-6 py-4"></th>
                            <th scope="col" class="px-6 py-4">Name</th>
                            <th scope="col" class="px-6 py-4">Last modified</th>
                            <th scope="col" class="px-6 py-4">Size</th>
                            <th scope="col" class="px-6 py-4"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            class="group border-b dark:border-neutral-500 cursor-pointer hover:bg-neutral-100 dark:hover:bg-neutral-800"
                            v-for="node in nodes"
                            :key="node.id"
                            draggable="true"
                            @dblclick="openFolder({ node })"
                            @dragover="onDragOver({ node, $event })"
                            @drop="onDrop({ node })"
                            @dragstart="onDragStart({ node })"
                            @dragend="onDragEnd"
                        >
                            <td class="pl-6 py-4">
                                <NodeIcon :node="node" />
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 font-medium">{{ node.name }}</td>
                            <td class="whitespace-nowrap px-6 py-4">{{ node.lastModified || '--' }}</td>
                            <td class="whitespace-nowrap px-6 py-4">{{ node.size || '--' }}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="invisible group-hover:visible">
                                    <NodeActions :node="node" />
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
