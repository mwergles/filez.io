<script setup>
import Draggable from 'vuedraggable'
import useNode from '@/composables/node'
import useDraggable from '@/composables/draggable'
import NodeIcon from '@/Components/NodeIcon.vue'
import NodeActions from '@/Components/NodeActions.vue'
import { formatBytes } from '@/lib/utils'

const {
    state,
    onStart,
    onEnd,
    onMove,
} = useDraggable()

const {
    nodes,
    navigateToNode,
} = useNode()

const openFolder = ({ node }) => {
    if (node.type !== 'folder') {
        return
    }

    navigateToNode({ node })
}
</script>

<template>
    <div
        v-if="nodes.length"
        class="flex flex-col"
    >
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full text-center text-sm font-light">
                        <thead
                            class="border-b bg-neutral-800 font-medium text-white dark:border-neutral-500 dark:bg-neutral-900">
                        <tr>
                            <th scope="col" class="pl-6 py-4"></th>
                            <th scope="col" class="px-6 py-4">Name</th>
                            <th scope="col" class="px-6 py-4">Size</th>
                            <th scope="col" class="px-2 py-4"></th>
                        </tr>
                        </thead>
                        <Draggable
                            tag="tbody"
                            :model-value="nodes"
                            item-key="id"
                            :move="onMove"
                            :sort="true"
                            @start="onStart"
                            @end="onEnd"
                            ghost-class="ghost"
                            :class="{ 'cursor-no-drop': state.isValidDropTarget }"
                        >
                            <template #item="{ element: node }">
                                <tr
                                    class="group border-b dark:border-neutral-500 cursor-grab hover:bg-neutral-100 dark:hover:bg-neutral-800"
                                    :class="{
                                    dragging: state.draggingId && state.draggingId === node.id,
                                    'cursor-no-drop': state.isValidDropTarget,
                                }"
                                    v-dbltap="() => openFolder({ node })"
                                    @dblclick="openFolder({ node })"
                                >
                                    <td class="pl-6 py-4">
                                        <NodeIcon :node="node" />
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">{{ node.name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ node.size ? formatBytes(node.size) : '--' }}</td>
                                    <td class="whitespace-nowrap px-2 py-4">
                                        <div class="md:invisible group-hover:visible">
                                            <NodeActions
                                                :node="node"
                                            />
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </Draggable>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
