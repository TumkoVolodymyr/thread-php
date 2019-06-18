import moment from 'moment';

export default {
    tweetsSortedByCreatedDate: state => Object.values(state.tweets).sort(
        (a, b) => (
            moment(b.created) - moment(a.created)
        )
    ),

    tweetsSortedByLikes: state => Object.values(state.tweets).sort(
        (a, b) => (
            moment(b.likesCount) - moment(a.likesCount)
        )
    ),

    getTweetById: state => id => state.tweets[id],

    isTweetOwner: (state, getters) => (tweetId, userId) => getters.getTweetById(tweetId).author.id === userId,

    tweetIsLikedByUser: (state, getters) => (tweetId, userId) => getters
        .getTweetById(tweetId)
        .likes
        .find(like => like.userId === userId) !== undefined
};
