import LikesAuthorModal from '../common/LikesAuthorModal.vue';

export default {
    methods: {
        showLikesAuthorModal(likes) {
            this.$modal.open({
                component: LikesAuthorModal,
                props: {
                    likes
                }
            });
        },
    }
};
