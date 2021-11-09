<template>
    <div class="main-app">
        <slot></slot>
    </div>
</template>

<script>
export default {

}
</script>
<style lang="scss" scoped>
    .main-app {
        margin: 0 auto;
        width: calc(min(100vw, 500px));
    }
</style>
