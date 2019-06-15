<template>
    <form
        class="form"
        @submit.prevent
        novalidate="true"
    >
        <b-field label="Email">
            <b-input
                v-model="user.email"
                name="email"
                autofocus
            />
        </b-field>

        <div class="has-text-centered">
            <button
                type="button"
                class="button is-primary is-rounded"
                :disabled="!user.email.trim()"
                @click="$emit('reset-password')"
            >
                Reset
            </button>
        </div>
    </form>
</template>

<script>

export default {
    name: 'EmailForm',

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
