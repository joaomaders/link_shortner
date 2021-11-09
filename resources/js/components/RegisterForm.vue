<template>
    <div class="main-div">
        <form action="submit" @submit.prevent class="register-form">
            <div class="field-div" ref="originalUrlDiv">
                <input type="text" id="original-url" v-model="originalUrl" placeholder="Paste a link to shorten it">
                <span class="error">{{ originalUrlError }}</span>
            </div>

            <div class="field-div checkbox">
                <label for="personalize"> Custom url? </label>
                <input type="checkbox" id="personalize" v-model="personalizeShortUrl">
            </div>

            <div class="field-div" ref="shortUrlDiv" v-if="personalizeShortUrl">
                <input type="text" id="original-url" placeholder="Enter a custom url" v-model="shortUrl">
                <span class="error">{{ shortUrlError }}</span>
            </div>

            <button @click="create()" v-if="!accessUrl">Create short url</button>
            <p class="access-link" v-else> You're access link is <a :href="accessUrl"> {{ accessUrl }} </a></p>
        </form>
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
                registeredUrls: [],
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
            generateRandomString (length, randomString="") {
                randomString += Math.random().toString(20).substr(2, length);

                this.shortUrlIsValid = true;

                if (randomString.length > length) return randomString.slice(0, length);

                this.shortUrl =  this.generateRandomString(length, randomString);
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
                    if (this.shortUrl === '') {
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

    .main-div {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px;

        .register-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;

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
                    gap: 20px;

                    input {
                        width: 15px;
                        height: 15px;
                    }
                }

                label {
                    font-size: 14px;
                    margin: 0;
                    font-weight: 500;
                    color: $black;
                }

                input {
                    height: 30px;
                    width: 100%;
                    border: solid 1px $black;
                    border-radius: 8px;
                    padding: 0 5px;
                    margin: 0;
                    font-size: 15px;
                    outline: none;
                    border: solid 2px $black;

                    &:focus {
                        border-color: green;
                    }
                }

                &.invalid {
                    input {
                        border: solid 2px red;
                        outline: none;
                    }
                    .error {
                        color: red;
                        font-size: 15px;
                    }
                }
            }

            button {
                border-radius: 16px;
                border: none;
                outline: none;
                width: 100%;
                height: 30px;

                background: lightgray;
                transition: all .2s;

                color: $black;
                font-size: 17px;
                font-weight: 700;

                margin-top: 10px;

                &:hover {
                    background: gray;
                    color: #FFFFFF;
                    transition: all .2s;
                }
            }

            .access-link {
                font-size: 15px;
                color: $black;
                font-weight: 500;
            }
        }
    }
</style>
