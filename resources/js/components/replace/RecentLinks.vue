<template>
    <div class="recent-urls">
        <h3 class="recent-links"> Recent links <span @click="getRecentLinks">refresh</span></h3>

        <div class="recent-url" v-for="url in registeredUrls" :key="url.id" @click="copyURL(url.short_url)">
            <div class="recent-url-left">
                <p class="short-url"> http://127.0.0.1:8000/{{ url.short_url }} </p>
                <p class="original-url"> {{ url.original_url }} </p>
            </div>

            <div class="recent-url-right">
                <p class="copy"> copy </p>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: ['hasNewItem'],
        data () {
            return {
                registeredUrls: []
            }
        },
        watch: {
            hasNewItem: function (newVal) {
                this.getRecentLinks(newVal);
            }
        },
        methods: {
            async copyURL (url) {
                try {
                    await navigator.clipboard.writeText(`http://127.0.0.1:8000/${url}`);
                } catch($e) {
                    alert('Cannot copy');
                }
            },
            getRecentLinks () {
                axios.get('/api/list')
                .then( response => {
                    this.registeredUrls = response.data;
                });
            }
        },
        mounted () {
            this.getRecentLinks();
        }
    }
</script>

<style lang="scss" scoped>
    $black: #3d3d3d;
    $background: #282a36;
    $gray:	#44475a;
    $lightgray:	#f8f8f2;
    $darkblue:	#6272a4;
    $cyan	:#8be9fd;
    $green	:#50fa7b;
    $orange	:#ffb86c;
    $pink	:#ff79c6;
    $purple	:#bd93f9;
    $red:#ff5555;
    $yellow:#f1fa8c;


    .recent-urls {
        padding: 30px 0;
        width: 100%;

        .recent-links {
            text-transform: uppercase;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: $lightgray;
            opacity: .8;

            span {
                color: $lightgray;

                text-transform: capitalize;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;

                &:hover {
                    color: #ffb86c;
                    opacity: 1;
                }
            }
        }

        .recent-url {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            gap: 15px;

            &:nth-child(odd) {
                background: rgba(255, 255, 255, 0.250);
            }

            &:nth-child(even) {
                background: rgba(255, 255, 255, 0.05);
            }

            &:hover {
                background: $gray;
                cursor: pointer;
                .recent-url-right {
                    .copy {
                        color: #ffb86c;
                        opacity: 1;
                    }
                }
            }

            &-left {
                .short-url {
                    font-size: 13px;
                    color: $lightgray;
                    font-weight: 500;
                    margin: 0;
                }

                .original-url {
                    margin: 0;
                    font-size: 11px;
                    color: $lightgray;
                    font-weight: 400;
                    opacity: 0.3;
                }
            }

            &-right {
                .copy {
                    font-size: 15px;
                    font-weight: 600;
                    cursor: pointer;
                    color: $lightgray;
                    margin: 0;
                    opacity: .4;
                }
            }

        }
    }
</style>
