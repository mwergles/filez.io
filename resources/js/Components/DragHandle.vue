<template>
    <div class="p-4 cursor-move">
        <span class="inline-block w-4 h-2 before:background-gradient-x"></span>
    </div>
</template>

<style scoped>
span,
span::before {
    background-image: radial-gradient(black 40%, transparent 40%);
    background-size: 4px 4px;
    background-position: 0 100%;
    background-repeat: repeat-x;
}

span::before {
    content: '';
    display: block;
    width: 100%;
    height: 33%;
}
</style>
