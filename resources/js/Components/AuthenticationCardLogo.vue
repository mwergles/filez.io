<script setup>
import { Link } from '@inertiajs/vue3';
import LightningsIcon from '@icons/lightnings-flash.svg';
</script>

<template>
    <Link :href="'/'">
        <LightningsIcon class="inline-block h-20 w-auto" /> Filez.io
    </Link>
</template>
