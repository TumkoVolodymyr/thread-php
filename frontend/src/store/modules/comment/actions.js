import api from '@/api/Api';
import { SET_LOADING } from '../../mutationTypes';
import {
    SET_COMMENTS,
    SET_COMMENT,
    SET_COMMENT_IMAGE,
    ADD_COMMENT,
    DISLIKE_COMMENT,
    LIKE_COMMENT,
    DELETE_COMMENT
} from './mutationTypes';
import { INCREMENT_COMMENTS_COUNT, DECREMENT_COMMENTS_COUNT } from '../tweet/mutationTypes';
import { commentMapper } from '@/services/Normalizer';

export default {
    async fetchComments({ commit }, { tweetId, page }) {
        commit(SET_LOADING, true, { root: true });

        try {
            const comments = await api.get(`/tweets/${tweetId}/comments`, {
                direction: 'desc',
                page
            });

            commit(SET_COMMENTS, comments);
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve(comments);
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async addComment({ commit }, { tweetId, text }) {
        commit(SET_LOADING, true, { root: true });

        try {
            const comment = await api.post('/comments', { tweet_id: tweetId, body: text });

            commit(ADD_COMMENT, comment);
            commit(`tweet/${INCREMENT_COMMENTS_COUNT}`, tweetId, { root: true });
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve(commentMapper(comment));
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async deleteComment({ commit }, comment) {
        commit(SET_LOADING, true, { root: true });

        try {
            await api.delete(`/comments/${comment.id}`);

            commit(DELETE_COMMENT, comment.id);
            commit(`tweet/${DECREMENT_COMMENTS_COUNT}`, comment.tweetId, { root: true });
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve();
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async likeOrDislikeComment({ commit }, { id, user }) {
        commit(SET_LOADING, true, { root: true });

        try {
            const data = await api.put(`/comments/${id}/like`);

            if (data.status === 'added') {
                commit(LIKE_COMMENT, {
                    id,
                    user
                });
            } else {
                commit(DISLIKE_COMMENT, {
                    id,
                    userId: user.id
                });
            }

            commit(SET_LOADING, false, { root: true });

            return Promise.resolve();
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async uploadCommentImage({ commit }, { id, imageFile }) {
        commit(SET_LOADING, true, { root: true });

        try {
            const formData = new FormData();
            formData.append('image', imageFile);

            const comment = await api.post(`/comments/${id}/image`, formData);

            commit(SET_COMMENT_IMAGE, {
                id,
                imageUrl: comment.image_url
            });

            commit(SET_LOADING, false, { root: true });

            return Promise.resolve();
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },

    async editComment({ commit }, { id, body }) {
        commit(SET_LOADING, true, { root: true });

        try {
            const comment = await api.put(`/comments/${id}`, { body });

            commit(SET_COMMENT, comment);
            commit(SET_LOADING, false, { root: true });

            return Promise.resolve(commentMapper(comment));
        } catch (error) {
            commit(SET_LOADING, false, { root: true });

            return Promise.reject(error);
        }
    },
};
