import { SET_COMMENTS, SET_COMMENT, SET_COMMENT_IMAGE, ADD_COMMENT, DELETE_COMMENT, LIKE_COMMENT, DISLIKE_COMMENT } from './mutationTypes';
import { commentMapper } from '@/services/Normalizer';

export default {
    [SET_COMMENTS]: (state, comments) => {
        let commentsByIdMap = {};

        comments.forEach(comment => {
            commentsByIdMap = {
                ...commentsByIdMap,
                [comment.id]: commentMapper(comment)
            };
        });

        state.comments = commentsByIdMap;
    },

    [ADD_COMMENT]: (state, comment) => {
        state.comments = {
            ...state.comments,
            [comment.id]: commentMapper(comment)
        };
    },

    [DELETE_COMMENT]: (state, id) => {
        const comments = { ...state.comments };
        delete comments[id];
        state.comments = comments ;
    },

    [LIKE_COMMENT]: (state, { id, user }) => {
        state.comments[id].likesCount++;

        state.comments[id].likes.push({ userId: user.id, author: user });
    },

    [DISLIKE_COMMENT]: (state, { id, userId }) => {
        state.comments[id].likesCount--;

        state.comments[id].likes = state.comments[id].likes.filter(like => like.userId !== userId);
    },

    [SET_COMMENT_IMAGE]: (state, { id, imageUrl }) => {
        state.comments[id].imageUrl = imageUrl;
    },

    [SET_COMMENT]: (state, comment) => {
        state.comments = {
            ...state.comments,
            [comment.id]: commentMapper(comment)
        };
    },
};
