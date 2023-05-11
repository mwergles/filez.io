export default {
    async getNodes ({ nodeId = null }) {
        const { data } = await axios.get(`/api/node/${nodeId ?? ''}`)

        return data.data
    },

    async moveNode ({ node, target = {} }) {
        const { data } = await axios.patch(`/api/node/move/`, {
            nodeId: node.id,
            targetId: target?.id,
        })

        return data.data
    },

    async createFolder ({ name, targetId }) {
        const { data } = await axios.post(`/api/node/folder`, { name, targetId })

        return data.data
    },

    async uploadFile ({ file, targetId = null }) {
        const formData = new FormData()
        formData.append('file', file)

        if (targetId) {
            formData.append('targetId', targetId)
        }

        const { data } = await axios.post(`/api/node/file`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })

        return data.data
    },

    async renameNode ({ nodeId, name }) {
        const { data } = await axios.patch(`/api/node/${nodeId}`, { name })

        return data.data
    },

    async deleteNode ({ nodeId }) {
        const { data } = await axios.delete(`/api/node/${nodeId}`)

        return data.data
    }
}
