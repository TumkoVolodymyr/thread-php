// map api response into our custom format

export const userMapper = user => ({ ...user });

export const commentMapper = comment => ({
    ...comment,
    authorId: comment.author_id,
    created: comment.created_at,
    updated: comment.updated_at,
    author: userMapper(comment.author)
});

export const tweetMapper = tweet => ({
    ...tweet,
    imageUrl: tweet.image_url,
    created: tweet.created_at,
    author: userMapper(tweet.author),
    comments: tweet.comments.map(commentMapper)
});