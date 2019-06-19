<template>
    <div class="tweet box" @click="$emit('click', tweet)">
        <article class="media">
            <figure class="media-left">
                <router-link
                    v-if="tweet.author.avatar"
                    class="image is-64x64 is-square"
                    :to="{ name: 'user-page', params: { id: tweet.author.id } }"
                >
                    <img class="is-rounded fit" :src="tweet.author.avatar" alt="Author avatar">
                </router-link>

                <router-link v-else :to="{ name: 'user-page', params: { id: tweet.author.id } }">
                    <DefaultAvatar class="image is-64x64" :user="tweet.author" />
                </router-link>
            </figure>

            <div class="media-content">
                <div class="content">
                    <strong class="name">
                        {{ tweet.author.firstName }} {{ tweet.author.lastName }}
                    </strong>
                    <small class="nickname">@{{ tweet.author.nickname }}</small>
                    <small class="created">{{ tweet.created | createdDate }}</small>
                    <br>
                    {{ tweet.text }}
                    <figure
                        v-if="tweet.imageUrl"
                        class="image is-3by1 tweet-image"
                    >
                        <img :src="tweet.imageUrl" alt="Tweet image">
                    </figure>
                </div>

                <nav class="level is-mobile">
                    <div class="level-left auto-cursor">
                        <a class="level-item auto-cursor">
                            <span class="icon is-medium has-text-info"
                              :class="{
                                'has-text-danger': isCommented
                              }"
                            >
                                <font-awesome-icon icon="comments" />
                            </span>
                            {{ tweet.commentsCount }}
                        </a>
                        <a class="level-item auto-cursor">
                            <span class="icon is-medium has-text-info"
                              :class="{
                                'has-text-danger': tweetIsLikedByUser(tweet.id, user.id)
                              }"
                            >
                                <font-awesome-icon icon="heart" />
                            </span>
                            {{ tweet.likesCount }}
                        </a>
                        <ShareBtn :url="url" />
                    </div>
                </nav>
            </div>
        </article>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import DefaultAvatar from './DefaultAvatar.vue';
import ShareBtn from './ShareBtn.vue';

export default {
    name: 'TweetPreview',

    components: {
        DefaultAvatar,
        ShareBtn,
    },

    props: {
        tweet: {
            type: Object,
            required: true,
        },
        isCommented: {
            type: Boolean,
            required: true,
        }
    },

    data: () => ({
        commentByUserTweets: null
    }),

    computed: {
        ...mapGetters('auth', {
            user: 'getAuthenticatedUser'
        }),

        ...mapGetters('tweet', [
            'tweetIsLikedByUser'
        ]),

        url() {
            const rout = this.$router.resolve({
                name: 'tweet-page',
                params: { id: this.tweet.id },
            });
            return window.location.origin + rout.href;
        }
    },

};
</script>

<style lang="scss" scoped>
@import '../../styles/common';

.tweet {
    cursor: pointer;
    padding: 15px;
    border-radius: 5px;
    box-shadow: 5px 5px 5px 0 #00000020;
    transition: 0.2s ease-out all;

    &:hover {
        box-shadow: 1px 1px 0 0 #00000020;
    }

    &-image {
        margin: 12px 0 0 0;

        img {
            width: auto;
        }
    }

    .nickname {
        margin-left: 5px;
    }

    .created {
        margin-left: 5px;
    }
}
</style>
