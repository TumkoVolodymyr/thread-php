<?php

declare(strict_types=1);

namespace App\Action\Tweet;

use App\Action\Common\CommentTweet\LikeItemAction;
use App\Entity\Like;
use App\Repository\LikeRepository;
use App\Repository\TweetRepository;

final class LikeTweetAction extends LikeItemAction
{
    private $tweetRepository;

    public function __construct(TweetRepository $tweetRepository, LikeRepository $likeRepository)
    {
        parent::__construct($likeRepository);
        $this->tweetRepository = $tweetRepository;
    }

    protected function getItemId(int $id): int
    {
        return $this->tweetRepository->getById($id)->id;
    }

    protected function isExistLikeForItemByUser(int $itemId, int $userId): bool
    {
        return $this->likeRepository->existsForTweetByUser($itemId, $userId);
    }

    protected function deleteForItemByUser(int $itemId, int $userId): void
    {
        $this->likeRepository->deleteForTweetByUser($itemId, $userId);
    }

    protected function createLike(int $userId, int $itemId): Like
    {
        $like = new Like();
        $like->forTweet($userId, $itemId);
        return $like;
    }
}
