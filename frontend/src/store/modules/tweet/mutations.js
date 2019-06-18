import {
    RESET_TWEETS,
    SET_TWEETS,
    SET_TWEET_IMAGE,
    SET_TWEET,
    DELETE_TWEET,
    INCREMENT_COMMENTS_COUNT,
    DECREMENT_COMMENTS_COUNT,
    LIKE_TWEET,
    DISLIKE_TWEET
} from './mutationTypes';
import { tweetMapper } from '@/services/Normalizer';

export default {
    [RESET_TWEETS]: (state) => {
        state.tweets = {};
    },

    [SET_TWEETS]: (state, tweets) => {
        state.tweets = {
            ...state.tweets,
            ...tweets.reduce(
                (prev, tweet) => ({ ...prev, [tweet.id]: tweetMapper(tweet) }),
                {}
            ),
        };
    },

    [SET_TWEET_IMAGE]: (state, { id, imageUrl }) => {
        state.tweets[id].imageUrl = imageUrl;
    },

    [SET_TWEET]: (state, tweet) => {
        state.tweets = {
            ...state.tweets,
            [tweet.id]: tweetMapper(tweet)
        };
    },

    [DELETE_TWEET]: (state, id) => {
        delete state.tweets[id];
    },

    [INCREMENT_COMMENTS_COUNT]: (state, id) => {
        state.tweets[id].commentsCount++;
    },

    [DECREMENT_COMMENTS_COUNT]: (state, id) => {
        state.tweets[id].commentsCount--;
    },

    [LIKE_TWEET]: (state, { id, user }) => {
        state.tweets[id].likesCount++;

        state.tweets[id].likes.push({ userId: user.id, author: user });
    },

    [DISLIKE_TWEET]: (state, { id, userId }) => {
        state.tweets[id].likesCount--;

        state.tweets[id].likes = state.tweets[id].likes.filter(like => like.userId !== userId);
    }
};
