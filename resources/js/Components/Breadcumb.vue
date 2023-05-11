<script setup>
import { computed } from 'vue'
import useNode from '@/composables/node'
import { APP_NAME } from '@/constants'
import BreadcumbLink from '@/Components/BreadcumbLink.vue'
import BreadcumbItem from '@/Components/BreadcumbItem.vue'

const { navigateToNode, path } = useNode()

const lastNode = computed(() => path.value[path.value.length - 1])
</script>

<template>
    <!-- Root Node -->
    <span>
        <BreadcumbLink
            v-if="path.length > 0"
            :node="{ name: APP_NAME }"
            @navigateToNode="navigateToNode"
        />
        <BreadcumbItem
            v-else
            :node="{ name: APP_NAME }"
        />
    </span>

    <!-- Descendant Nodes -->
    <span
        v-for="node in path"
        :key="node.id"
    >
        /
        <BreadcumbLink
            v-if="node.id !== lastNode?.id"
            :node="node"
            @navigateToNode="navigateToNode({ node })"
        />
        <BreadcumbItem
            v-else
            :node="node"
        />
    </span>
</template>
