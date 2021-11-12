<template>
    <div class="main-app">
        <div class="width-div">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {

}
</script>
<style lang="scss" scoped>
    .main-app {
        padding: 30px 0;
        width: 100vw !important;
        padding: 0 5vw;
        min-height: 100vh;
        height: auto;
        background-color: #6272a4;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        overflow: hidden;

        .width-div {
            width: calc(min(100vw, 500px));
        }
    }
</style>
