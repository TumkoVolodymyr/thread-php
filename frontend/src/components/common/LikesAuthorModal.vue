<template>
    <div class="LikesAuthorModal">
        <div class="modal-content">
            <div class="box">
                <article class="media">
                    <div class="media-content">
                        <div class="content">
                            <div v-if="!likes.length">
                                <p>There is no likes yet</p>
                            </div>
                            <div v-for="like in likes" class="tooltip author-container">
                                <b-tooltip :label=like.author.nickname animated>
                                    <figure class="media-left">
                                        <p
                                            v-if="like.author.avatar"
                                            class="image is-64x64 is-square"
                                        >
                                            <img
                                                class="is-rounded fit"
                                                :src="like.author.avatar"
                                                alt="Author avatar"
                                            >
                                        </p>
                                        <p v-else >
                                            <DefaultAvatar class="image is-64x64" :user="like.author" />
                                        </p>
                                    </figure>
                                </b-tooltip>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</template>

<script>
import DefaultAvatar from '../common/DefaultAvatar.vue';
export default {
    name: 'LikesAuthorModal',

    components: {
        DefaultAvatar
    },

    props: {
        likes: {
            type: Array,
            required: true
        }
    },
};
</script>

<style scoped>
    .tooltip {
        position: relative;
        display: inline-block;
    }

    .content {
        margin: 25px 0;
    }

    .content figure {
        margin: 0.5em;
    }

</style>
