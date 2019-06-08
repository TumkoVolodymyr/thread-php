import { SET_COMMENTS, ADD_COMMENT, LIKE_COMMENT, DISLIKE_COMMENT } from './mutationTypes';
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

    [LIKE_COMMENT]: (state, { id, userId }) => {
        state.comments[id].likesCount++;

        state.comments[id].likes.push({ userId });
    },

    [DISLIKE_COMMENT]: (state, { id, userId }) => {
        state.comments[id].likesCount--;

        state.comments[id].likes = state.comments[id].likes.filter(like => like.userId !== userId);
    }
};
