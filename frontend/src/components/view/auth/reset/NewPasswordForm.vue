<template>
    <form
        class="form"
        @submit.prevent
        novalidate="true"
    >
        <b-field label="Password">
            <b-input
                type="password"
                v-model="user.password"
            />
        </b-field>

        <div class="has-text-centered">
            <button
                type="button"
                class="button is-primary is-rounded"
                :disabled="!user.password.trim()"
                @click="$emit('set-password')"
            >
                Change
            </button>
        </div>
    </form>
</template>

<script>

export default {
    name: 'NewPasswordForm',

    props: {
        user: {
            type: Object,
            required: true,
        },
    },
};
</script>

<style scoped>
.error {
    padding: 10px 0;
}
</style>
