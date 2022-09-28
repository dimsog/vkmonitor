<template>
    <div class="spinner"></div>
</template>

<style scoped>
.spinner {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 9px solid;
    border-color: #f0f2f4;
    border-right-color: #e1e5ea;
    animation: spinner-animation 1s infinite linear;
}

@keyframes spinner-animation {
    to {
        transform: rotate(1turn);
    }
}
</style>
