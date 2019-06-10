<template>
    <div class="modal-card" @keyup.ctrl.exact.enter="save">
        <header class="modal-card-head">
            <p class="modal-card-title">Edit</p>
        </header>

        <section class="modal-card-body">
            <div class="error has-text-danger" v-if="errorMessage">{{ errorMessage }}</div>

            <b-field label="Text">
                <b-input type="textarea" v-model="text" :placeholder="comment.body" />
            </b-field>

            <b-field class="file">
                <b-upload v-model="image">
                    <a class="button is-primary">
                        <b-icon pack="fa" icon="upload" />
                        <span>Edit image</span>
                    </a>
                </b-upload>
                <span class="file-name" v-if="image">
                    {{ image.name }}
                </span>
            </b-field>
        </section>

        <footer class="modal-card-foot">
            <b-button type="is-primary" :disabled="!text.trim()" @click="save">
                Save
            </b-button>
        </footer>
    </div>
</template>

<script>
import { mapActions } from 'vuex';

export default {
    name: 'EditCommentForm',

    props: {
        comment: {
            type: Object,
            required: true,
        },
    },

    data: () => ({
        text: '',
        image: null,
        errorMessage: ''
    }),

    computed: {

    },

    methods: {
        ...mapActions('comment', [
            'editComment',
            'uploadCommentImage',
        ]),

        async save() {
            try {
                const comment = await this.editComment({
                    id: this.comment.id,
                    body: this.text,
                });

                if (this.image !== null) {
                    await this.uploadCommentImage({
                        id: comment.id,
                        imageFile: this.image
                    });
                }

                this.clearInput();
                this.clearImage();
                this.$emit('edit-comment-finished');
            } catch (error) {
                this.showErrorMessage(error.message);
            }
        },

        showErrorMessage(msg) {
            this.errorMessage = msg;
        }
    },
};
</script>

<style lang="scss" scoped>
.textarea {
    min-height: 60px;
}
</style>
