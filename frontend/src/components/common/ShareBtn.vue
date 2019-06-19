<template>
    <ul class="share aricle__share">
        <li class="share__item">
            <a target="_blank" class="share__item-link share-tg single-card__photo-fav" title="Share on Telegram"
               :href="'https://telegram.me/share/url?url='+url">
                <svg style="margin-top: 9px" width="19" enable-background="new -41 164.9 512 512" version="1.1" viewBox="-41 164.9 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path class="fill-hover" fill="#969696" d="m323.1 314c-6.7 0.2-72.1 27.2-245.1 103.8l66.6 22.4 137.1-85.2 4.8-1 1.9 2.9-2.4 3.3-110.5 103.8-7.1 53.3 39.5-36.2 70.9 53.3 47.6-223.7c5-7.7 11.3-11.6 14.8-10 2.9 1.3 3.7 6.2 4.3 9.5 0.8 4.7 0.1 8.6-0.5 10.9l-41.4 209.4c-0.3 1.7-0.8 4.2-1.9 7.1-1.2 3.1-3.3 8.6-8.6 12.4-3.6 2.6-7.2 3.4-9.5 3.8-8.8 1.7-15.8-1.4-18.1-2.4-4-1.8-6.8-4.1-8.6-5.7l-46.2-36.7-29.5 26.2-5.2 4.3c-0.8 0.6-1.9 1.3-3.3 1.9-4.9 2-9.4 0.9-10.9 0.5-4.4-1.3-6.9-4-8.6-5.7-3-3.2-4.5-6.6-5.2-8.6l-22.4-69.5-59.6-20.8c-1.3-0.5-3-1.4-4.8-2.9-7-5.7-7.6-14.4-7.6-15.2-0.3-7.6 3.6-12.8 4.8-14.3 5.6-7.1 13.5-8.3 15.2-8.6l225.6-96 8.1-3c3.5-2.1 6.8-3.2 9-3.8 2.6-0.7 6.7-1.7 11.9-1 2.8 0.4 5.7 0.8 8.6 2.9 3.1 2.2 4.5 5 5.9 7.9 1.1 2.2 1.7 3.3 1.4 4.3s-1.4 2.9-11.4 4.9c-4.3 1-6.5 1.4-9.6 1.5z"></path>
                        <circle class="stroke-hover" fill="none" stroke="#969696" stroke-width="35" stroke-miterlimit="10" cx="217.7" cy="420.5" r="233.1"></circle>
                    </g>
                </svg>
            </a>
        </li>
    </ul>
</template>

<script>

export default {
    name: 'ShareBtn',

    props: {
        url: {
            type: String,
            required: true
        }
    }

};
</script>

<style lang="scss" scoped>

</style>
