<template>
    <div class="container">
        <form action="submit" @submit.prevent class="register-form">
            <div class="field-div" ref="originalUrlDiv">
                <label for="original-url"> Original url </label>
                <input type="text" id="original-url" placeholder="https://www.google.com.br" v-model="originalUrl">
                <span class="error">{{ originalUrlError }}</span>
            </div>

            <div class="field-div" ref="shortUrlDiv">
                <label for="original-url"> Short url </label>
                <input type="text" id="original-url" placeholder="mywebsite" v-model="shortUrl">
                <span class="error">{{ shortUrlError }}</span>
            </div>

            <button @click="create()" v-if="!accessUrl">Create short url</button>
            <p v-else> You're access link is <a :href="accessUrl"> {{ accessUrl }} </a></p>
        </form>
    </div>
</template>

<script>
    import _ from 'lodash';
    import axios from 'axios';

    export default {
        data () {
            return {
                originalUrl: 'https://',
                originalUrlError: null,
                originalUrlIsValid: false,
                shortUrl: '',
                shortUrlError: null,
                shortUrlIsValid: false,
                accessUrl: undefined
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
            validateOriginalUrl (newVal) {
                if (newVal.startsWith('https://')) {
                    this.$refs.originalUrlDiv.classList.remove('invalid');
                    this.originalUrlError = "";
                    this.originalUrlIsValid = true;

                    if (newVal.length < 12) {
                        this.$refs.originalUrlDiv.classList.add('invalid');
                        this.originalUrlError = "must be at least 12 characters";
                        this.originalUrlIsValid = false;
                    }
                } else {
                    this.$refs.originalUrlDiv.classList.add('invalid');
                    this.originalUrlError = "must start with 'https://'";
                    this.originalUrlIsValid = false;
                }
            },
            validateShortUrl (newVal) {
                if (newVal.length >= 6) {
                    this.$refs.shortUrlDiv.classList.remove('invalid');
                    this.shortUrlError = "";
                    this.shortUrlIsValid = true;
                } else {
                    this.$refs.shortUrlDiv.classList.add('invalid');
                    this.shortUrlError = "must have at least 6 characteres";
                    this.shortUrlIsValid = false;
                }
            },
            async create () {
                if (this.shortUrl === '' || this.originalUrl === 'https://' ) {
                    this.validateShortUrl(this.shortUrl);
                    this.validateOriginalUrl(this.originalUrl);
                }

                axios.get(`/api/url/exists/${this.shortUrl}`)
                .then( response => {
                    if (response.data === 1 ) {
                        this.$refs.shortUrlDiv.classList.add('invalid');
                        this.shortUrlError = "url already registered";
                        this.shortUrlIsValid = true;
                        return;
                    } else {
                        this.shortUrlIsValid = false;
                    }
                });

                if (this.shortUrlIsValid && this.originalUrlIsValid) {

                    console.log(this.shortUrl, this.originalUrl);
                    axios.post('api/url/store', {
                        short_url: this.shortUrl,
                        original_url: this.originalUrl
                    })
                    .then(() => {
                        this.accessUrl = `/api/url/access/${this.shortUrl}`;
                    });
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .container {
        .register-form {
            .field-div {
                display: flex;
                flex-direction: column;

                &.invalid {
                    input {
                        border: solid 2px red;
                        outline: none;
                    }
                    .error {
                        color: red;
                    }
                }
            }
        }
    }
</style>
