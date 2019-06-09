<template>
    <article class="media">
        <figure class="media-left">
            <router-link
                v-if="comment.author.avatar"
                class="image is-48x48 is-square"
                :to="{ name: 'user-page', params: { id: comment.author.id } }"
            >
                <img class="is-rounded fit" :src="comment.author.avatar">
            </router-link>

            <router-link v-else :to="{ name: 'user-page', params: { id: comment.author.id } }">
                <DefaultAvatar class="image is-48x48" :user="comment.author" />
            </router-link>
        </figure>
        <div class="media-content">
            <div class="columns">
                <div class="column">
                    <div class="content">
                        <p>
                            <strong>
                                {{ comment.author.firstName }} {{ comment.author.lastName }}
                            </strong>
                            <br>
                            {{ comment.body }}
                        <figure
                            v-if="comment.imageUrl"
                            class="image is-4by1 content-image"
                        >
                            <img :src="comment.imageUrl" alt="Tweet image">
                        </figure>
                        <br>
                        <b-tooltip label="Like" animated>
                            <a class="level-item" @click="onLikeOrDislikeComment(comment.id)">
                                <span
                                    class="icon is-medium has-text-info"
                                    :class="{
                                        'has-text-danger': commentIsLikedByUser(comment.id, user.id)
                                    }"
                                >
                                    <font-awesome-icon icon="heart" />
                                </span>
                                {{ comment.likesCount }}
                            </a>
                        </b-tooltip>
                        <br>
                        <small class="has-text-grey">
                            {{ comment.created | createdDate }}
                        </small>
                        </p>
                    </div>
                </div>

                <div v-if="isCommentOwner(comment.id, user.id)" class="column is-narrow is-12-mobile">
                    <div class="buttons">
                        <b-button type="is-warning" @click="$emit('edit-comment', comment)">Edit</b-button>
                        <b-button type="is-danger">Delete</b-button>
                    </div>
                </div>
            </div>
        </div>
    </article>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import DefaultAvatar from '../../common/DefaultAvatar.vue';

export default {
    name: 'Comment',

    components: {
        DefaultAvatar,
    },

    computed: {
        ...mapGetters('auth', {
            user: 'getAuthenticatedUser'
        }),

        ...mapGetters('comment', [
            'commentIsLikedByUser',
            'isCommentOwner'
        ]),

    },

    methods: {

        ...mapActions('comment', [
            'likeOrDislikeComment',
        ]),

        async onLikeOrDislikeComment(comentId) {
            try {
                await this.likeOrDislikeComment({
                    id: comentId,
                    userId: this.user.id
                });
            } catch (error) {
                console.error(error.message);
            }
        }
    },

    props: {
        comment: {
            type: Object,
            required: true,
        },
    },
};
</script>

<style lang="scss" scoped>
nav {
    margin-left: -8px;
}

.content {
    p {
        margin-bottom: 0;
    }

    &-image {
        margin: 12px 0 0 0;

        img {
            width: auto;
        }
    }
}
</style>
