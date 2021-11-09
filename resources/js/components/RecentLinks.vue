<template>
    <div class="recent-urls">
        <h3 class="recent-links"> Recent links </h3>

        <div class="recent-url" v-for="url in registeredUrls" :key="url.id">
            <div class="recent-url-left">
                <p class="short-url"> http://127.0.0.1:8000/{{ url.short_url }} </p>
                <p class="original-url"> {{ url.original_url }} </p>
            </div>

            <div class="recent-url-right">
                <p class="copy" @click="copyURL(url.short_url)"> copy </p>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data () {
            return {
                registeredUrls: []
            }
        },
        methods: {
            async copyURL(url) {
                try {
                    await navigator.clipboard.writeText(`http://127.0.0.1:8000/${url}`);
                } catch($e) {
                    alert('Cannot copy');
                }
            },
        },
        mounted () {
            axios.get('/api/list')
            .then( response => {
                this.registeredUrls = response.data;
            });
        }
    }
</script>

<style lang="scss" scoped>
    $black: #3d3d3d;

    .recent-urls {
        padding: 30px 0;

        .recent-links {
            text-transform: uppercase;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: center;
        }

        .recent-url {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 20px;
            gap: 15px;

            &:nth-child(odd) {
                background: rgba(211, 211, 211, 0.665);
            }

            &:nth-child(even) {
                background: rgba(211, 211, 211, 0.880);
            }

            &:hover {
                background: gray;
            }

            &-left {
                .short-url {
                    font-size: 15px;
                    color: $black;
                    font-weight: 500;
                    margin: 0;
                }

                .original-url {
                    margin: 0;
                    font-size: 13px;
                    color: yellow;
                    font-weight: 400;
                }
            }

            &-right {
                .copy {
                    font-size: 15px;
                    font-weight: 600;
                    cursor: pointer;
                    &:hover {
                        color: red;
                    }
                }
            }

        }
    }
</style>
