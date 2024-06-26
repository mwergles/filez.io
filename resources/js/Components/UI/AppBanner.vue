<script setup>
import { computed, ref, watch, watchEffect } from 'vue'
import { usePage } from '@inertiajs/vue3'
import useError from '@/composables/error.composable'

const show = ref(true)
const style = computed(() => {
    const defaultStyle = usePage().props.jetstream.flash?.bannerStyle || 'success'
    const customStyle = errorMessage.value ? 'danger' : null

    return customStyle ? customStyle : defaultStyle
})
const message = computed(() => {
    const defaultMessage = usePage().props.jetstream.flash?.banner || ''
    return defaultMessage || errorMessage.value
})
const shouldDisplay = computed(() => {
    return show.value && message.value
})

const { errorMessage, clearError } = useError()

watch(message, async () => {
    show.value = true
})

watchEffect(() => {
    if (errorMessage.value) {
        show.value = true
        setTimeout(() => {
            show.value = false
        }, 5000)
    }
})

const dismiss = () => {
    show.value = false
    clearError()
}
</script>

<template>
    <div>
        <div v-if="shouldDisplay" :class="{ 'bg-indigo-500': style === 'success', 'bg-red-700': style === 'danger' }">
            <div class="max-w-screen-xl mx-auto py-2 px-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="w-0 flex-1 flex items-center min-w-0">
                        <span
                            :class="{ 'bg-indigo-600': style === 'success', 'bg-red-600': style === 'danger' }"
                            class="flex p-2 rounded-lg">
                            <svg
                                v-if="style === 'success'" class="h-5 w-5 text-white" fill="none"
                                stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                                    stroke-linejoin="round"/>
                            </svg>

                            <svg
                                v-if="style === 'danger'" class="h-5 w-5 text-white" fill="none"
                                stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" stroke-linecap="round"
                                    stroke-linejoin="round"/>
                            </svg>
                        </span>

                        <p class="ml-3 font-medium text-sm text-white truncate">
                            {{ message }}
                        </p>
                    </div>

                    <div class="shrink-0 sm:ml-3">
                        <button
                            :class="{ 'hover:bg-indigo-600 focus:bg-indigo-600': style === 'success', 'hover:bg-red-600 focus:bg-red-600': style === 'danger' }"
                            aria-label="Dismiss"
                            class="-mr-1 flex p-2 rounded-md focus:outline-none sm:-mr-2 transition"
                            type="button"
                            @click.prevent="dismiss"
                        >
                            <svg
                                class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
