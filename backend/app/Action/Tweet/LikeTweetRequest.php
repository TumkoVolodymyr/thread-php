<?php

declare(strict_types=1);

namespace App\Action\Tweet;


use App\Action\Common\CommentTweet\LikeItemRequestInterface;

final class LikeTweetRequest implements LikeItemRequestInterface
{
    private $tweetId;

    public function __construct(int $tweetId)
    {
        $this->tweetId = $tweetId;
    }

    public function getTweetId(): int
    {
        return $this->tweetId;
    }

    public function getItemId(): int
    {
        return $this->getTweetId();
    }
}
