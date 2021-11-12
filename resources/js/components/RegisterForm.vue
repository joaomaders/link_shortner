<template>
    <div class="main-div">
        <div v-if="accessUrl">
            <p class="access-link">
                You're access link is <a :href="accessUrl"> {{ accessUrl }} </a>

                <span class="copy" @click="copyURL">
                    <font-awesome-icon icon="copy" />
                </span>
            </p>

            <p @click="reset" class="new-url"> New url </p>
        </div>
        <form action="submit" @submit.prevent class="register-form" v-else>
            <div class="main-field" ref="originalUrlDiv">
                <div class="left-part">
                    <input type="text" id="original-url" v-model="originalUrl" placeholder="Paste a link to shorten it">
                    <span class="error">{{ originalUrlError }}</span>
                </div>

                <div class="right-part">
                    <button class="submit-button" @click="create()"> Shorten </button>
                </div>
            </div>

            <div class="field-div checkbox">
                <label for="personalize"> Custom url? </label>
                <input type="checkbox" id="personalize" v-model="personalizeShortUrl">
            </div>

            <div class="field-div" ref="shortUrlDiv" v-if="personalizeShortUrl">
                <input type="text" id="original-url" placeholder="Enter a custom url" v-model="shortUrl">
                <span class="error">{{ shortUrlError }}</span>
            </div>
        </form>

        <links-list :hasNewItem="accessUrl" v-if="!accessUrl"></links-list>
    </div>
</template>

<script>
    import _ from 'lodash';
    import axios from 'axios';

    export default {
        data () {
            return {
                originalUrl: '',
                originalUrlError: null,
                originalUrlIsValid: false,
                shortUrl: '',
                shortUrlError: null,
                shortUrlIsValid: false,
                accessUrl: undefined,
                personalizeShortUrl: false
            }
        },
        watch: {
            originalUrl: _.debounce(function (newVal) {
                this.validateOriginalUrl(newVal);
            }, 250),
            shortUrl: _.debounce(function (newVal) {
                this.validateShortUrl(newVal);
            }, 250),
        },
        methods: {
            makeToast(append = false, url) {
                this.$bvToast.toast(url, {
                    title: 'Link copied!',
                    autoHideDelay: 3000,
                    appendToast: append
                });
            },
            async copyURL () {
                try {
                    await navigator.clipboard.writeText(`https://127.0.0.1:8000/${this.accessUrl}`);
                    this.makeToast(false, this.accessUrl);
                } catch($e) {
                    alert('Cannot copy');
                }
            },

            generateRandomString (length, randomString="") {
                randomString += Math.random().toString(20).substr(2, length);

                this.shortUrlIsValid = true;

                if (randomString.length > length) return randomString.slice(0, length);

                this.shortUrl = this.generateRandomString(length, randomString);
            },

            reset () {
                this.originalUrl = '',
                this.originalUrlError = null,
                this.originalUrlIsValid = false,
                this.shortUrl = '',
                this.shortUrlError = null,
                this.shortUrlIsValid = false,
                this.accessUrl = undefined,
                this.personalizeShortUrl = false
            },

            validateOriginalUrl (newVal) {
                if (newVal.startsWith('https://')) {
                    this.$refs.originalUrlDiv.classList.remove('invalid');
                    this.originalUrlError = "";
                    this.originalUrlIsValid = true;

                    if (newVal.length < 12) {
                        this.$refs.originalUrlDiv.classList.add('invalid');
                        this.originalUrlError = "Min size is 12 characters";
                        this.originalUrlIsValid = false;
                    }
                } else {
                    this.$refs.originalUrlDiv.classList.add('invalid');
                    this.originalUrlError = "Link must start with 'https://'";
                    this.originalUrlIsValid = false;
                }
            },

            validateShortUrl (newVal) {
                if (newVal.length >= 6) {
                    this.$refs.shortUrlDiv.classList.remove('invalid');
                    this.shortUrlError = "";
                    this.shortUrlIsValid = true;

                    if (newVal.length > 12) {
                        this.$refs.shortUrlDiv.classList.add('invalid');
                        this.shortUrlError = "Max size is 12 characters";
                        this.shortUrlIsValid = false;
                    }
                } else {
                    this.$refs.shortUrlDiv.classList.add('invalid');
                    this.shortUrlError = "Min size is 6 characteres";
                    this.shortUrlIsValid = false;
                }
            },

            async create () {
                if (this.originalUrl === '' ) {
                    this.validateOriginalUrl(this.originalUrl);
                }

                if (this.originalUrlIsValid) {
                    if (this.shortUrl === '' || this.shortUrl.length < 6) {
                        this.generateRandomString(6);
                    }

                    axios.get(`/api/url/exists/${this.shortUrl}`)
                    .then( response => {
                        if (response.data === 1 ) {
                            this.$refs.shortUrlDiv.classList.add('invalid');
                            this.shortUrlError = "url already registered";
                            this.shortUrlIsValid = false;
                        } else {
                            this.shortUrlIsValid = true;

                            if (this.shortUrlIsValid && this.originalUrlIsValid) {

                                console.log(this.shortUrl, this.originalUrl);
                                axios.post('api/url/store', {
                                    short_url: this.shortUrl,
                                    original_url: this.originalUrl
                                })
                                .then(() => {
                                    this.accessUrl = `http://127.0.0.1:8000/${this.shortUrl}`;
                                })
                                .then(() => {
                                    this.$root.$emit('refreshRecentLinksList');
                                });
                            }
                        }
                    });
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    $black: #3d3d3d;
    $background: #282a36;
    $gray:	#44475a;
    $lightgray:	#f8f8f2;
    $lightgray2:	#f8f8f282;
    $darkblue:	#6272a4;
    $cyan	:#8be9fd;
    $green	:#50fa7b;
    $orange	:#ffb86c;
    $pink	:#ff79c6;
    $purple	:#bd93f9;
    $red:#ff5555;
    $yellow:#f1fa8c;

    .main-div {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px 0;

        .access-link {
            font-size: 15px;
            color: $lightgray;
            font-weight: 500;

            .copy {
                margin-left: 5px;
                color: $lightgray2;
                cursor: pointer;

                &:hover {
                    color: $orange;
                }
            }

            a {
                color: $cyan;
            }
        }

        .new-url {
            color: $orange;
            text-align: center;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;

            &:hover {
                color: $lightgray;
            }
        }

        .register-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;

            .main-field {
                width: 100%;
                display: flex;
                align-items: flex-start;
                justify-content: center;
                background: transparent;
                overflow: hidden;

                &.invalid {
                    .left-part {
                        input {
                            border-color: $red;
                        }
                    }
                }

                .left-part {
                    width: 80%;

                    input {
                        height: 40px;
                        width: 100%;
                        font-size: 15px;

                        border: none;
                        outline: none;
                        background: transparent;
                        border-bottom: solid 2px $lightgray2;
                        color: $lightgray;

                        &::placeholder {
                            color: $lightgray2;
                        }
                    }

                    .error {
                        color: $red;
                        font-size: 15px;
                        font-weight: 500;
                    }
                }

                .right-part {
                    width: 20%;

                    .submit-button {
                        background: $lightgray2;

                        width: 100%;
                        height: 40px;
                        font-size: 15px;
                        font-weight: 600;
                        border: none;
                        outline: none;
                        color: #FFFFFF;

                        &:hover {
                            background: $lightgray;
                            color: $orange;
                        }
                    }
                }
            }

            .field-div {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                gap: 0px;
                width: 100%;

                &.checkbox {
                    flex-direction: row;
                    align-items: center;
                    justify-content: flex-start;
                    gap: 10px;

                    input {
                        width: 12.5px;
                        height: 12.5px;
                        border: none;
                        outline: none;
                        cursor: pointer;
                    }
                }

                label {
                    font-size: 13px;
                    margin: 0;
                    font-weight: 600;
                    color: $lightgray;
                }

                input {
                    height: 40px;
                    width: 100%;
                    font-size: 15px;

                    border: none;
                    outline: none;
                    background: transparent;
                    border-bottom: solid 2px $lightgray2;
                    color: $lightgray;

                    &::placeholder {
                        color: $lightgray2;
                    }
                }

                &.invalid {
                    input {
                        border-color: $red;
                        outline: none;
                    }
                    .error {
                        color: $red;
                        font-size: 15px;
                    }
                }
            }
        }
    }
</style>
