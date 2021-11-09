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
        width: 100vw;
        height: 100vh;
        background-color: #6272a4;
        display: flex;
        align-items: center;
        justify-content: center;

        .width-div {
            width: calc(min(100vw, 500px));
        }
    }
</style>
