<template>
    <div class="comments-container">
        <infinite-loading direction="top" :distance=1 @infinite="infiniteHandler">
            <div slot="no-more" />
            <div slot="no-results" />
            <div slot="spinner" />
        </infinite-loading>
        <Comment
            v-for="comment in comments"
            :key="comment.id"
            :comment="comment"
            @edit-comment="$emit('edit-comment', comment)"
        />
    </div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading';
import Comment from './Comment';

export default {
    name: 'CommentList',

    data: () => ({
        isFirst: true
    }),

    components: {
        Comment,
        InfiniteLoading
    },

    props: {
        comments: {
            type: Array,
            required: true,
        },
    },

    methods: {
        infiniteHandler($state) {
            this.$emit('infinite', $state);
        },
    }
};
</script>

<style lang="scss" scoped>
.comments-container {
    max-height: 300px;
    overflow: scroll;
}
</style>
