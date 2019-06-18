<template>
    <div class="tweets-container">
        <div class="row settings-container">
            <div class="col-sm text-center">
                <span class="align-middle">
                    Card view
                </span>
                <span class="align-middle">
                    <label class="switch">
                        <input v-model="isCardTweetView" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </span>
            </div>
            <div v-if="orderBy" class="col-sm text-center">
                <span class="align-middle">
                    Order by
                </span>
                <span class="align-middle">
                    <b-button
                        class="btn-add-tweet btn-order"
                        rounded
                        :class="{ active: isActive('creation') }"
                        size="is-small"
                        @click="onOrderByClick('creation')"
                    >
                        Creation
                    </b-button>
                </span>
                <span class="align-middle">
                    <b-button
                        class="btn-add-tweet btn-order"
                        rounded
                        :class="{ active: isActive('popularity') }"
                        size="is-small"
                        @click="onOrderByClick('popularity')"
                    >
                        Popularity
                    </b-button>
                </span>
            </div>
        </div>
        <transition-group
            name="slide-prev"
            tag="div"
            :class="{
                'flex': isCardTweetView
            }"
        >
            <template v-for="tweet in tweets">
                <component
                    v-bind:is="currentTweetViewComponent"
                    :key="tweet.id"
                    :tweet="tweet"
                    :isCommented="tweetIsCommented(tweet.id)"
                    @click="onTweetClick"
                ></component>
            </template>
        </transition-group>
        <infinite-loading @infinite="infiniteHandler">
            <div slot="no-more" />
            <div slot="no-results" />
            <div slot="spinner" />
        </infinite-loading>
    </div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading';
import TweetPreview from './TweetPreview.vue';
import TweetCardPreview from './TweetCardPreview.vue';

export default {
    name: 'TweetPreviewList',

    props: {
        tweets: {
            type: Array,
            required: true
        },
        commentedTweets: {
            type: Array,
            required: true
        },
        orderBy: {
            type: String,
            required: false
        }
    },

    data: () => ({
        isCardTweetView: false
    }),

    components: {
        TweetPreview,
        InfiniteLoading,
    },

    methods: {
        onTweetClick(tweet) {
            this.$router.push({ name: 'tweet-page', params: { id: tweet.id } });
        },

        infiniteHandler($state) {
            this.$emit('infinite', $state);
        },

        tweetIsCommented(tweetId) {
            return this.commentedTweets.indexOf(tweetId) > -1;
        },

        isActive(value) {
            return value === this.orderBy;
        },

        onOrderByClick(value) {
            this.$emit('onOrderByClick', value);
        }
    },

    computed: {
        currentTweetViewComponent() {
            return this.isCardTweetView ? TweetCardPreview : TweetPreview;
        }
    }
};

</script>

<style scoped lang="scss">
.tweets-container {
    padding-bottom: 20px;
}
/* The switch - the box around the slider */
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

/* Hide default HTML checkbox */
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

/* The slider */
.slider {
    position: absolute;
    cursor: pointer;
    top: 4px;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 2px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}

input:checked + .slider {
    background-color: #14bc61;
}

input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

.col-sm.text-center {
    display: flex;
    align-items: center;
}
.align-middle {
    margin-right: 5px;
}
.flex {
    display: flex;
    flex-wrap: wrap;
}

.btn-order.active {
    background: lightgrey;
}

.settings-container {
    display: flex;
    justify-content: space-between;
}
</style>
