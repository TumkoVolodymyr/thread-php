<template>
    <section class="hero is-fullheight">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-mobile is-centered">
                    <div class="column is-three-quarters-mobile is-two-thirds-tablet is-one-third-desktop">
                        <div class="box shadow-box">
                            <p class="subtitle">
                                Remembered password?
                                <router-link class="link" to="/auth/sign-up">
                                    Sign up
                                </router-link>
                            </p>
                            <div v-if="isInit">
                                <EmailForm
                                    :user="user"
                                    @reset-password="onReset"
                                />
                            </div>
                            <div v-if="isCheckCode">
                                <ResetCodeForm
                                    :user="user"
                                    @check-code="onCheckCode"
                                />
                            </div>
                            <div v-if="isPassword">
                                <NewPasswordForm
                                    :user="user"
                                    @set-password="onSetPassword"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { mapActions } from 'vuex';
import showStatusToast from '../components/mixin/showStatusToast';
import EmailForm from '@/components/view/auth/reset/EmailForm.vue';
import ResetCodeForm from '@/components/view/auth/reset/ResetCodeForm.vue';
import NewPasswordForm from '@/components/view/auth/reset/NewPasswordForm.vue';

export default {
    name: 'ResetPassword',

    mixins: [showStatusToast],

    components: {
        EmailForm,
        ResetCodeForm,
        NewPasswordForm,
    },

    data: () => ({
        user: {
            email: '',
            code: '',
            password: '',
            token: null,
        },
        state: 'init'
    }),

    computed: {
        isInit() {
            return this.state === 'init';
        },
        isCheckCode() {
            return this.state === 'check';
        },
        isPassword() {
            return this.state === 'password';
        }
    },

    methods: {
        ...mapActions('auth', [
            'reset',
            'checkCode',
            'changePassword',
        ]),

        onReset() {
            this.reset(this.user)
                .then((message) => {
                    this.showSuccessMessage(message);
                    this.state = 'check';
                })
                .catch(error => this.showErrorMessage(error.message));
        },

        onCheckCode() {
            this.checkCode(this.user)
                .then((response) => {
                    this.showSuccessMessage(response.message);
                    this.user.token = response.token;
                    this.state = 'password';
                })
                .catch(error => this.showErrorMessage(error.message));
        },

        onSetPassword() {
            this.changePassword(this.user)
                .then(() => {
                    this.showSuccessMessage('Welcome!');
                    this.state = 'changed';
                    this.$router.push({ path: '/' });
                })
                .catch(error => this.showErrorMessage(error.message));
        },
    },
};
</script>

<style scoped>
</style>
