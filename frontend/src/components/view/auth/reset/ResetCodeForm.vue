<template>
    <form
        class="form"
        @submit.prevent
        novalidate="true"
    >
        <p class="subtitle">
            Code was sent by email
        </p>
        <b-field label="Reset code">
            <b-input
                v-model="user.code"
                name="code"
                autofocus
            />
        </b-field>

        <div class="has-text-centered">
            <button
                type="button"
                class="button is-primary is-rounded"
                :disabled="!user.code.trim()"
                @click="$emit('check-code')"
            >
                Confirm
            </button>
        </div>
    </form>
</template>

<script>

export default {
    name: 'ResetCodeForm',

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
