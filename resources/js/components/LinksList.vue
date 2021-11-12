<template>
    <div class="links-list">
        <div class="search-div" :class="{ 'active': isSearchFieldActive }">
            <font-awesome-icon icon="search" @click="toggleSearchFieldActive" class="search-icon" v-if="!isSearchFieldActive" />

            <input type="text" v-model="searchTerm" class="search">
        </div>

        <h3 class="empty-set" v-if="registeredUrls.length === 0 && isOnlyRecent === false"> No urls yet, <a href="/"> start shorting </a> </h3>
        <h3 class="recent-links" v-else>
            {{ isOnlyRecent === true ? 'Recent' : 'Registered' }} links
            <span @click="getRecentLinks" class="refresh" :class="{ 'is-rotating': isLoading }"> <font-awesome-icon icon="sync" /> refresh</span>
        </h3>

        <div class="recent-urls">
            <div class="loading-layer" v-if="isLoading">
                <div class="lds-dual-ring"></div>
            </div>

            <div class="recent-url" v-for="url in filteredUrls" :key="url.id">
                <div class="recent-url-left">
                    <a :href="url.short_url">
                        <p class="short-url"> http://127.0.0.1:8000/{{ url.short_url }} </p>
                    </a>

                    <a :href="url.short_url">
                        <p class="original-url"> {{ url.original_url }} </p>
                    </a>

                    <p class="original-url"> Acessos: {{ url.access_counter }} </p>
                </div>

                <div class="recent-url-right" @click="copyURL(url.short_url)">
                    <p class="copy"> <font-awesome-icon icon="copy" /> </p>
                </div>
            </div>
        </div>

        <a href="/links-list" v-if="isOnlyRecent">
            <div class="bottom-option"> see all registered urls </div>
        </a>
        <a href="/" v-else>
            <div class="bottom-option"> start shorting </div>
        </a>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: {
            hasNewItem: {

            },
            isOnlyRecent: {
                type: Boolean,
                default: true
            }
        },
        data () {
            return {
                registeredUrls: [],
                isLoading: false,
                searchTerm: '',
                isSearchFieldActive: false
            }
        },
        watch: {
            hasNewItem: function (newVal) {
                this.getRecentLinks(newVal);
            }
        },
        computed: {
            filteredUrls () {
                return this.registeredUrls.filter(item => item.original_url.includes(this.searchTerm) || item.short_url.includes(this.searchTerm));
            }
        },
        methods: {
            makeToast(append = false, url) {
                this.$bvToast.toast(`https://127.0.0.1:8000/${url}`, {
                    title: 'Link copied!',
                    autoHideDelay: 3000,
                    appendToast: append
                });
            },
            async copyURL (url) {
                try {
                    await navigator.clipboard.writeText(`https://127.0.0.1:8000/${url}`);
                    this.makeToast(false, url);
                } catch($e) {
                    alert('Cannot copy');
                }
            },
            toggleSearchFieldActive () {
                this.isSearchFieldActive = !this.isSearchFieldActive;
            },
            getRecentLinks () {
                this.isLoading = true;
                axios.get(`/api/${this.isOnlyRecent === true ? 'list' : 'urls'}`)
                .then( response => {
                    this.registeredUrls = response.data;
                })
                .then(() => {
                    this.isLoading = false;
                });
            }
        },
        mounted () {
            this.getRecentLinks();

            this.$root.$on('refreshRecentLinksList', this.getRecentLinks());
        }
    }
</script>

<style lang="scss" scoped>
    $black: #3d3d3d;
    $background: #282a36;
    $gray:	#44475a;
    $lightgray:	#f8f8f2;
    $lightgray2:	#f8f8f22f;
    $darkblue:	#6272a4;
    $cyan	:#8be9fd;
    $green	:#50fa7b;
    $orange	:#ffb86c;
    $pink	:#ff79c6;
    $purple	:#bd93f9;
    $red:#ff5555;
    $yellow:#f1fa8c;

    .links-list {
        padding: 30px 0;
        width: 100%;

        .search-div {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 30px 0;
            width: 300px;
            height: 30px;
            position: relative;
            overflow: hidden;

            &.active {
                .search {
                    z-index: 1;
                    left: 0;
                }
            }

            .search-icon {
                color: $lightgray2;
                cursor: pointer;
                z-index: 3;
                margin-left: 5px;

                &:hover {
                    color: $orange;
                }
            }

            .search {
                width: 300px;
                height: 30px;
                background-color: $lightgray2;
                outline: none;
                border: none;

                position: absolute;
                left: -300px;
                top: 15px;
                transition: all 0.4s;
            }
        }

        .empty-set {
            color: $lightgray;
            text-align: center;
            font-size: 30px;

            a {
                color: $orange;
            }
        }

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

            .refresh {
                color: $lightgray;
                text-transform: capitalize;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;

                &.is-rotating {
                    .fa-sync {
                        animation: sync .15s ease infinite;
                    }
                }

                .fa-sync {
                    font-size: 11px;
                    margin-right: 5px;

                    @keyframes sync {
                        from {
                            transform: rotate(0deg);
                        }
                        to {
                            transform: rotate(360deg);
                        }
                    }
                }

                &:hover {
                    color: #ffb86c;
                    opacity: 1;

                    .fa-sync {
                        animation: sync .3s ease;
                    }
                }
            }
        }

        .recent-urls {
            position: relative;

            .loading-layer {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: $darkblue;
                opacity: .6;
                z-index: 3;

                display: flex;
                align-items: center;
                justify-content: center;

                .lds-dual-ring {
                    display: inline-block;
                    width: 80px;
                    height: 80px;

                    &::after {
                        content: " ";
                        display: block;
                        width: 64px;
                        height: 64px;
                        margin: 8px;
                        border-radius: 50%;
                        border: 6px solid #fdd;
                        border-color: #fdd transparent #fdd transparent;
                        animation: lds-dual-ring 1.2s linear infinite;
                    }
                }

                @keyframes lds-dual-ring {
                    0% {
                        transform: rotate(0deg);
                    }
                    100% {
                        transform: rotate(360deg);
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

                    .original-url {
                        color: $orange;
                        opacity: 1;
                    }
                }

                &-left {
                    a {
                        text-decoration: none;
                    }

                    .short-url {
                        font-size: 13px;
                        color: $lightgray;
                        font-weight: 500;
                        margin: 0;

                        &:hover {
                            color: $cyan;
                        }
                    }

                    .original-url {
                        margin: 0;
                        font-size: 11px;
                        color: $lightgray;
                        font-weight: 400;
                        opacity: 0.3;

                        &:hover {
                            opacity: 1;
                            text-decoration: underline;
                        }
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

                        &:hover {
                            color: $orange;
                            opacity: 1;
                        }
                    }
                }
            }
        }

        .bottom-option {
            color: $orange;
            font-size: 13px;
            font-weight: 400;
            text-align: center;
            padding: 15px 0 0 0;
            cursor: pointer;
            &:hover {
                text-decoration: underline;
            }
        }
    }
</style>
