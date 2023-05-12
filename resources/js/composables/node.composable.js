import { ref } from 'vue'
import useError from '@/composables/error.composable'
import nodeApi from '@/api/node.api'

// shared state
const currentNodeId = ref(null)
const nodes = ref([])
const path = ref([])
const loading = ref(false)

export default function useNode () {
    const { setError } = useError()

    async function loadNodesForPath ({ nodeId = null } = {}) {
        try {
            const nodeToFetch = nodeId ?? currentNodeId.value
            const { nodes: _nodes, path: _path } = await nodeApi.getNodes({ nodeId: nodeToFetch })

            nodes.value = _nodes
            path.value = _path
        } catch (error) {
            setError({ message: 'Failed to browse files', error })
            console.error(error)
        }
    }

    async function navigateToNode ({ node }) {
        if (loading.value) {
            return
        }

        loading.value = true

        try {
            currentNodeId.value = node?.id

            await loadNodesForPath({ nodeId: node?.id })
        } catch (error) {
            setError({ message: 'Failed to navigate', error })
            console.error(error)
        } finally {
            loading.value = false
        }
    }

    async function moveNode ({ node, target }) {
        if (loading.value) {
            return
        }

        loading.value = true

        try {
            await nodeApi.moveNode({ node, target })
            await loadNodesForPath({ nodeId: target ? currentNodeId.value : node.parent_id })
        } catch (error) {
            setError({ message: `Failed to move ${node.type}`, error })
            console.error(error)
        } finally {
            loading.value = false
        }
    }

    async function createFolder ({ name }) {
        if (loading.value) {
            return
        }

        loading.value = true

        try {
            const targetId = currentNodeId.value ?? null

            await nodeApi.createFolder({ name, targetId })
            await loadNodesForPath({ nodeId: targetId })
        } catch (error) {
            setError({ message: `Failed to create folder ${name}`, error })
            console.error(error)
        } finally {
            loading.value = false
        }
    }

    async function uploadFile ({ file }) {
        if (loading.value) {
            return
        }

        loading.value = true

        try {
            const targetId = currentNodeId.value ?? null

            await nodeApi.uploadFile({ file, targetId })
            await loadNodesForPath({ nodeId: targetId })
        } catch (error) {
            setError({ message: `Failed to upload ${file.name}`, error })
            console.error(error)
        } finally {
            loading.value = false
        }
    }

    async function renameNode ({ node, newNodeName }) {
        if (loading.value) {
            return
        }

        loading.value = true

        try {
            await nodeApi.renameNode({ nodeId: node.id, name: newNodeName })
            await loadNodesForPath()
        } catch (error) {
            setError({ message: `Failed to rename ${node.type}`, error })
            console.error(error)
        } finally {
            loading.value = false
        }
    }

    async function deleteNode ({ node }) {
        if (loading.value) {
            return
        }

        loading.value = true

        try {
            await nodeApi.deleteNode({ nodeId: node.id })
            await loadNodesForPath()
        } catch (error) {
            setError({ message: `Failed to delete ${node.type}`, error })
            console.error(error)
        } finally {
            loading.value = false
        }
    }

    return {
        nodes,
        path,
        loading,
        currentNodeId,
        loadNodesForPath,
        navigateToNode,
        moveNode,
        createFolder,
        uploadFile,
        renameNode,
        deleteNode,
    }
}
