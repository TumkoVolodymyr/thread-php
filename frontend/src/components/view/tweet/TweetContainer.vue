<template>
    <div v-if="tweet">
        <article class="media box tweet">
            <figure class="media-left">
                <router-link
                    v-if="tweet.author.avatar"
                    class="image is-64x64 is-square"
                    :to="{ name: 'user-page', params: { id: tweet.author.id } }"
                >
                    <img
                        class="is-rounded fit"
                        :src="tweet.author.avatar"
                        alt="Author avatar"
                    >
                </router-link>

                <router-link v-else :to="{ name: 'user-page', params: { id: tweet.author.id } }">
                    <DefaultAvatar class="image is-64x64" :user="tweet.author" />
                </router-link>
            </figure>

            <div class="media-content">
                <div class="columns">
                    <div class="column">
                        <div class="content">
                            <p class="tweet-text">
                                <strong>{{ tweet.author.firstName }} {{ tweet.author.lastName }}</strong>
                                <br>
                                <small class="has-text-grey">
                                    {{ tweet.created | createdDate }}
                                </small>
                                <br>
                                {{ tweet.text }}
                            </p>
                            <figure v-if="tweet.imageUrl" class="image is-3by1 tweet-image">
                                <img
                                    :src="tweet.imageUrl"
                                    alt="Tweet image"
                                    @click="showImageModal"
                                >
                            </figure>
                            <nav class="level is-mobile">
                                <div class="level-left">
                                    <b-tooltip label="Comments" animated>
                                        <a class="level-item auto-cursor">
                                            <span
                                                class="icon is-medium has-text-info"
                                                :class="{
                                                    'has-text-danger': tweet.isCommented
                                                }"
                                            >
                                                <font-awesome-icon icon="comments" />
                                            </span>
                                            {{ tweet.commentsCount }}
                                        </a>
                                    </b-tooltip>

                                    <b-tooltip label="Like" animated>
                                        <a class="level-item" @click="onLikeOrDislikeTweet">
                                            <span
                                                class="icon is-medium has-text-info"
                                                :class="{
                                                    'has-text-danger': tweetIsLikedByUser(tweet.id, user.id)
                                                }"
                                            >
                                                <font-awesome-icon icon="heart" />
                                            </span>
                                            <span @click.stop.prevent="onShowLikesAuthor">
                                                {{ tweet.likesCount }}
                                            </span>
                                        </a>
                                    </b-tooltip>
                                    <ShareBtn :url="url" />
                                </div>
                            </nav>
                        </div>
                    </div>

                    <div v-if="isTweetOwner(tweet.id, user.id)" class="column is-narrow is-12-mobile">
                        <div class="buttons">
                            <b-button type="is-warning" @click="onEditTweet">Edit</b-button>
                            <b-button type="is-danger" @click="onDeleteTweet">Delete</b-button>
                        </div>
                    </div>
                </div>

                <CommentList
                    :comments="comments"
                    @infinite="infiniteHandler"
                    @edit-comment="onEditComment"
                />

                <NewCommentForm :tweet-id="tweet.id" />
            </div>
        </article>

        <b-modal :active.sync="isImageModalActive">
            <p class="image is-4by3">
                <img class="fit" :src="tweet.imageUrl">
            </p>
        </b-modal>

        <b-modal :active.sync="isEditTweetModalActive" has-modal-card>
            <EditTweetForm :tweet="tweet" />
        </b-modal>

        <b-modal v-if="editableComment" :active.sync="isEditCommentModalActive" has-modal-card>
            <EditCommentForm
                :comment="editableComment"
                @edit-comment-finished="onEditCommentFinished"
            />
        </b-modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import CommentList from './CommentList.vue';
import NewCommentForm from './NewCommentForm.vue';
import EditCommentForm from './EditCommentForm.vue';
import EditTweetForm from './EditTweetForm.vue';
import DefaultAvatar from '../../common/DefaultAvatar.vue';
import ShareBtn from '../../common/ShareBtn.vue';
import showStatusToast from '../../mixin/showStatusToast';
import { pusher } from '@/services/Pusher';
import { SET_COMMENT } from '@/store/modules/comment/mutationTypes';
import showLikesAuthorModal from '../../mixin/showLikesAuthorModal';

export default {
    name: 'TweetContainer',

    components: {
        EditCommentForm,
        CommentList,
        NewCommentForm,
        EditTweetForm,
        DefaultAvatar,
        ShareBtn,
    },

    mixins: [showStatusToast, showLikesAuthorModal],

    data: () => ({
        page: 1,
        isEditTweetModalActive: false,
        editableComment: null,
        isImageModalActive: false
    }),

    async created() {
        try {
            await this.fetchTweetById(this.$route.params.id);

            const channel = pusher.subscribe(`private-tweet.${this.tweet.id}`);

            channel.bind('comment.added', (data) => {
                this.$store.commit(`comment/${SET_COMMENT}`, data.comment);
            });
        } catch (error) {
            console.error(error.message);
        }
    },

    computed: {
        ...mapGetters('auth', {
            user: 'getAuthenticatedUser'
        }),

        ...mapGetters('tweet', [
            'getTweetById',
            'isTweetOwner',
            'tweetIsLikedByUser'
        ]),

        ...mapGetters('comment', [
            'getCommentsByTweetId',
        ]),

        isEditCommentModalActive: {
            get() { return this.editableComment !== null; },

            set: function (val) {
                this.editableComment = val;
            }
        },

        comments() {
            return  this.getCommentsByTweetId(this.tweet.id);
        },

        tweet() {
            return this.getTweetById(this.$route.params.id);
        },

        url() {
            const rout = this.$router.resolve({
                name: 'tweet-page',
                params: { id: this.tweet.id },
            });
            return window.location.origin + rout.href;
        }
    },

    methods: {
        ...mapActions('tweet', [
            'fetchTweetById',
            'deleteTweet',
            'likeOrDislikeTweet'
        ]),

        ...mapActions('comment', [
            'fetchComments',
        ]),

        onEditTweet() {
            this.isEditTweetModalActive = true;
        },

        onEditComment(comment) {
            this.editableComment = comment;
        },

        onEditCommentFinished() {
            this.editableComment = null;
        },

        onDeleteTweet() {
            this.$dialog.confirm({
                title: 'Deleting tweet',
                message: 'Are you sure you want to <b>delete</b> your tweet? This action cannot be undone.',
                confirmText: 'Delete Tweet',
                type: 'is-danger',

                onConfirm: async () => {
                    try {
                        await this.deleteTweet(this.tweet.id);

                        this.showSuccessMessage('Tweet deleted!');

                        this.$router.push({ name: 'feed' });
                    } catch {
                        this.showErrorMessage('Unable to delete tweet!');
                    }
                }
            });
        },

        showImageModal() {
            this.isImageModalActive = true;
        },

        async onLikeOrDislikeTweet() {
            try {
                await this.likeOrDislikeTweet({
                    id: this.tweet.id,
                    user: this.user
                });
            } catch (error) {
                console.error(error.message);
            }
        },

        onShowLikesAuthor() {
            this.showLikesAuthorModal(this.tweet.likes);
        },

        async infiniteHandler($state) {
            try {
                const comments = await this.fetchComments({ tweetId: this.tweet.id, page: this.page });
                if (comments.length) {
                    this.page += 1;
                    $state.loaded();
                } else {
                    $state.complete();
                }
            } catch (error) {
                this.showErrorMessage(error.message);
                $state.complete();
            }
        },
    },
};
</script>

<style lang="scss" scoped>
@import "~bulma/sass/utilities/initial-variables";
@import "../../../styles/common";

.tweet-image {
    margin: 12px 0 0 0;

    img {
        width: auto;
        cursor: pointer;
    }
}

.buttons {
    .button {
        @media screen and (max-width: $tablet) {
            font-size: 0.8rem;
        }
    }
}

.tweet-text {
    max-width: 100%;
}

.activity {
    margin-bottom: 16px;
}

.content {
    figure {
        margin-top: 0;
        margin-bottom: .75rem;
    }
}

.column {
    padding-bottom: 0;
}

.level-left ul {
    list-style: none;
    margin: initial;
    margin-left: .75rem;
}
</style>
