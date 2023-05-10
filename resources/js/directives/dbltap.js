const DOUBLE_TAP_INTERVAL = 500

function detectDoubleTap ({ onDbltap } = {}) {
    let lastTap = 0
    let timeout

    return function detectDoubleTap (event) {
        const curTime = new Date().getTime()
        const tapLen = curTime - lastTap

        if (tapLen < DOUBLE_TAP_INTERVAL && tapLen > 0) {
            event.preventDefault()
            onDbltap?.()
            console.log('Double tapped!')
        } else {
            timeout = setTimeout(() => {
                clearTimeout(timeout)
            }, DOUBLE_TAP_INTERVAL)
        }

        lastTap = curTime
    }
}

export default {
    beforeMount ($el, binding) {
        if (typeof binding?.value !== 'function') {
            return
        }

        $el.addEventListener('touchend', detectDoubleTap({ onDbltap: binding.value }), { passive: false })
    },

    unmounted ($el) {
        $el.removeEventListener('touchend', detectDoubleTap(), { passive: false })
    },
}
