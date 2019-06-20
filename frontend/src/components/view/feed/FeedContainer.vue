<template>
    <div class="feed-container">
        <div class="navigation">
            <b-button
                class="btn-add-tweet"
                rounded
                size="is-medium"
                type="is-danger"
                icon-left="twitter"
                icon-pack="fab"
                @click="onAddTweetClick"
            >
                Tweet :)
            </b-button>
            <b-button
                class="btn-add-tweet"
                rounded
                size="is-medium"
                type="is-primary"
                @click="onLikedTweetsClick"
            >
                {{likedTweetsBtnText}}
            </b-button>
        </div>

        <TweetPreviewList
            :orderBy="orderBy"
            :tweets="tweets"
            @onOrderByClick="onOrderByClick"
            @infinite="infiniteHandler" />

        <b-modal :active.sync="isNewTweetModalActive" has-modal-card>
            <NewTweetForm />
        </b-modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import TweetPreviewList from '../../common/TweetPreviewList.vue';
import NewTweetForm from './NewTweetForm.vue';
import { pusher } from '@/services/Pusher';
import { SET_TWEET } from '@/store/modules/tweet/mutationTypes';
import showStatusToast from '@/components/mixin/showStatusToast';

export default {
    name: 'FeedContainer',

    mixins: [showStatusToast],

    components: {
        TweetPreviewList,
        NewTweetForm,
    },

    data: () => ({
        isNewTweetModalActive: false,
        page: 1,
        isLikedTweets: false,
        orderBy: 'creation'
    }),

    async created() {
        await this.initTweets();

        const channel = pusher.subscribe('private-tweets');

        channel.bind('tweet.added', (data) => {
            this.$store.commit(`tweet/${SET_TWEET}`, data.tweet);
        });
    },

    beforeDestroy() {
        pusher.unsubscribe('private-tweets');
    },

    computed: {
        ...mapGetters('tweet', [
            'tweetsSortedByCreatedDate',
            'tweetsSortedByLikes'
        ]),

        tweets() {
            return this.orderBy === 'creation' ? this.tweetsSortedByCreatedDate : this.tweetsSortedByLikes;
        },

        likedTweetsBtnText() {
            return !this.isLikedTweets ? 'Liked tweets' : 'All tweets';
        }
    },

    methods: {
        ...mapActions('tweet', [
            'fetchTweets',
            'resetTweets',
        ]),

        onAddTweetClick() {
            this.showAddTweetModal();
        },

        async onLikedTweetsClick() {
            this.resetTweets();
            this.page = 1;
            this.isLikedTweets = !this.isLikedTweets;
            await this.initTweets();
        },

        showAddTweetModal() {
            this.isNewTweetModalActive = true;
        },

        async infiniteHandler($state) {
            try {
                const page = {
                    page: this.page + 1,
                    isLiked: this.isLikedTweets,
                    sort: this.orderBy
                };
                const tweets = await this.fetchTweets(page);

                if (tweets.length) {
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

        async initTweets() {
            this.resetTweets();
            try {
                const page = { page: 1, isLiked: this.isLikedTweets, sort: this.orderBy };
                await this.fetchTweets(page);
            } catch (error) {
                this.showErrorMessage(error.message);
            }
        },

        async onOrderByClick(value) {
            this.orderBy = value;
            await this.initTweets();
        }
    },
};
</script>

<style scoped lang="scss">
@import '~bulma/sass/utilities/initial-variables';

.navigation {
    padding: 10px 0;
    margin-bottom: 20px;
}

.modal-card {
    border-radius: 6px;
}

.btn-add-tweet {
    transition: 0.2s ease-out all;
    box-shadow: 1px 5px 5px 0 #00000020;
    margin-right: 10px;

    &:hover {
        box-shadow: 1px 1px 0 0 #00000020;
    }

    @media screen and (max-width: $tablet) {
        font-size: 1rem;
    }
}
</style>
