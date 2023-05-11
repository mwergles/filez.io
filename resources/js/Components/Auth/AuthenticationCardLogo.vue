<script setup>
import { Link } from '@inertiajs/vue3';
import LightningsIcon from '@icons/logo.svg';
import { APP_NAME } from '@/constants'
</script>

<template>
    <Link :href="'/'">
        <LightningsIcon class="inline-block h-20 w-auto" /> {{ APP_NAME }}
    </Link>
</template>
