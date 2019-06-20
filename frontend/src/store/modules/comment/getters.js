import moment from 'moment';

export default {
    getCommentsSortedByCreatedDateAsc: state => Object
        .values(state.comments)
        .sort(
            (a, b) => (
                moment(a.created).isBefore(moment(b.created)) ? -1 : 1
            )
        ),

    getCommentsByTweetId: (state, getters) => tweetId => getters
        .getCommentsSortedByCreatedDateAsc
        .filter(comment => comment.tweetId === tweetId),

    getCommentById: state => id => state.comments[id],

    isCommentOwner: (state, getters) => (commentId, userId) => getters.getCommentById(commentId).authorId === userId,

    commentIsLikedByUser: (state, getters) => (commentId, userId) => getters
        .getCommentById(commentId)
        .likes
        .find(like => like.userId === userId) !== undefined
};
