import axios from 'axios'
import BadRequestError from '@/api/errors/BadRequestError'

const instance = axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    }
})

instance.interceptors.response.use(
    response => response,
    error => {
        if ([401, 403].includes(error.response?.status)) {
            window.location.href = '/login'
            return
        }

        if (error.response?.status >= 400 && error.response?.status < 500) {
            return Promise.reject(new BadRequestError(error.response.data.message))
        }

        return Promise.reject(error)
    }
)

export default instance
