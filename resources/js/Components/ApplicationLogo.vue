<script setup>
import LightningsIcon from '@icons/lightnings-flash.svg'
import { APP_NAME } from '@/constants'
</script>

<template>
    <LightningsIcon class="inline-block h-20 w-auto" />
    <span class="inline-block text-4xl font-semibold text-gray-600 dark:text-gray-400">{{ APP_NAME }}</span>
</template>
