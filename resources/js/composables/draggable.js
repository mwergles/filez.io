import { ref, computed, reactive } from 'vue'
import useNode from '@/composables/node'

const { moveNode, nodes } = useNode()
let nodeBeingMoved = null
let targetNode = null

export default function useDraggable () {
    const isValidDropTarget = computed(() => state.draggingId && state.isHoveringFolder)

    const state = reactive({
        nodeList: nodes,
        draggingId: null,
        isHoveringFolder: false,
        isValidDropTarget,
    })

    const onStart = (context) => {
        nodeBeingMoved = state.nodeList[context.oldIndex]
        state.draggingId = nodeBeingMoved.id
    }

    const onEnd = async () => {
        if (!targetNode) {
            resetState()
            return
        }

        try {
            await moveNode({ node: nodeBeingMoved, target: targetNode })
        } finally {
            resetState()
        }
    }

    const onMove = (context) => {
        const { relatedContext } = context
        const { element: nodeBeingHovered } = relatedContext

        state.isHoveringFolder = nodeBeingHovered.type === 'folder'

        if (!state.isHoveringFolder) {
            targetNode = null
            return false
        }

        targetNode = nodeBeingHovered

        // prevents draggable default behaviour
        return false
    }

    const resetState = () => {
        nodeBeingMoved = null
        targetNode = null
        state.isHoveringFolder = false
        state.draggingId = null
    }

    return {
        onStart,
        onMove,
        onEnd,
        state,
    }
}
