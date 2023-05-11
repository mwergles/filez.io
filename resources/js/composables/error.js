import { ref } from 'vue'

const errorMessage = ref(null)

export default function useError () {
    const setError = (message) => {
        errorMessage.value = message
    }

    const clearError = () => {
        errorMessage.value = null
    }

    return {
        errorMessage,
        setError,
        clearError
    }
}
