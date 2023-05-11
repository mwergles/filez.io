import { onMounted, watch } from 'vue'
import useNode from '@/composables/node.composable'

export default function useNavigation () {
    const { loadNodesForPath, currentNodeId } = useNode()

    function getNodeIdFromUrl () {
        const urlParams = new URLSearchParams(window.location.search)

        return urlParams.get('path')
    }

    function updateUrlPath (nodeId) {
        const url = new URL(window.location)

        if (nodeId) {
            url.searchParams.set('path', nodeId)
        } else {
            url.searchParams.delete('path')
        }

        window.history.pushState({}, '', url.href)
    }

    onMounted(async () => {
        // get the current node id from the url query string
        const nodeId = getNodeIdFromUrl()
        if (nodeId) {
            currentNodeId.value = nodeId
        }

        await loadNodesForPath({ nodeId: currentNodeId.value })
    })

    watch(currentNodeId, (newValue, oldValue) => {
        if (newValue === oldValue) {
            return
        }

        updateUrlPath(newValue)
    })
}
