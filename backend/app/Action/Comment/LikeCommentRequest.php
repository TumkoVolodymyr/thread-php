<?php

declare(strict_types=1);

namespace App\Action\Comment;


use App\Action\Common\CommentTweet\LikeItemRequestInterface;

final class LikeCommentRequest implements LikeItemRequestInterface
{
    private $commentId;

    public function __construct(int $commentId)
    {
        $this->commentId = $commentId;
    }

    public function getCommentId(): int
    {
        return $this->commentId;
    }

    public function getItemId(): int
    {
        return $this->getCommentId();
    }
}
