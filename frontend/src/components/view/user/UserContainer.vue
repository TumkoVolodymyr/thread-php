<template>
    <div class="user-container">
        <TweetPreviewList
            :tweets="tweets"
            @infinite="infiniteHandler"
            :commentedTweets="commentedTweets"
        />
        <NoContent :show="noContent" title="No tweets yet :)" />
    </div>
</template>

<script>
import { mapActions } from 'vuex';
import TweetPreviewList from '@/components/common/TweetPreviewList.vue';
import NoContent from '@/components/common/NoContent.vue';
import showStatusToast from '@/components/mixin/showStatusToast';

export default {
    name: 'UserContainer',

    mixins: [showStatusToast],

    components: {
        TweetPreviewList,
        NoContent
    },

    data: () => ({
        tweets: [],
        page: 1,
        commentedTweets: [],
        noContent: false,
    }),

    async created() {
        try {
            const data = {
                userId: this.$route.params.id,
                params: {
                    page: 1
                }
            };
            this.tweets = await this.fetchTweetsByUserId(data);
            this.updateCommentedTweets(data);

            if (!this.tweets.length) {
                this.noContent = true;
            }
        } catch (error) {
            this.showErrorMessage(error.message);
        }
    },

    methods: {
        ...mapActions('tweet', [
            'fetchTweetsByUserId',
            'fetchCommentedTweetsByUserId',
        ]),

        async infiniteHandler($state) {
            try {
                const data = {
                    userId: this.$route.params.id,
                    params: {
                        page: this.page + 1
                    }
                };
                const tweets = await this.fetchTweetsByUserId(data);
                this.updateCommentedTweets(data);

                if (tweets.length) {
                    this.tweets.push(...tweets);
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

        async updateCommentedTweets(data) {
            const commentedTweets = await this.fetchCommentedTweetsByUserId(data);
            this.commentedTweets = [...this.commentedTweets, ...commentedTweets];
        }
    },
};
</script>

<style scoped>
</style>
