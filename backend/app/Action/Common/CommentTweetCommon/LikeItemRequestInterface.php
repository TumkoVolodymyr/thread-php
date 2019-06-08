<?php

declare(strict_types=1);

namespace App\Action\Common\CommentTweet;

interface LikeItemRequestInterface
{
    public function getItemId(): int;
}
