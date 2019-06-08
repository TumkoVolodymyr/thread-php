<?php

declare(strict_types=1);

namespace App\Action\Common\CommentTweet;

final class LikeItemResponse
{
    private $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
