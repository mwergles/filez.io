import { onMounted, ref, watch } from 'vue'
import { getNodeIdFromUrl, updateUrlPath } from '@/lib/utils'
import useNode from '@/composables/node'

export default function useNavigation () {
    const { loadNodesForPath, currentNodeId } = useNode()

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
