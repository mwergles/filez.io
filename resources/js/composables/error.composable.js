import { ref } from 'vue'
import BadRequestError from '@/api/errors/BadRequestError'

const errorMessage = ref(null)

export default function useError () {
    const setError = ({ message, error = null }) => {
        if (error instanceof BadRequestError) {
            message += `: ${error.message}`
        }

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
