<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Tweet;

final class LikeRepository
{
    public function save(Like $like): Like
    {
        $like->save();

        return $like;
    }

    public function existsForTweetByUser(int $tweetId, int $userId): bool
    {
        return Like::where([
            'likeable_id' => $tweetId,
            'likeable_type' => Tweet::class,
            'user_id' => $userId
        ])->exists();
    }

    public function deleteForTweetByUser(int $tweetId, int $userId): void
    {
        Like::where([
            'likeable_id' => $tweetId,
            'likeable_type' => Tweet::class,
            'user_id' => $userId
        ])->delete();
    }

    public function existsForCommentByUser(int $objectId, int $userId): bool
    {
        return $this->findForCommentByUser($objectId, $userId)->exists();
    }

    public function deleteForCommentByUser(int $objectId, int $userId): void
    {
        $this->findForCommentByUser($objectId, $userId)->delete();
    }

    public function findForCommentByUser(int $tweetId, int $userId)
    {
        return Like::where([
            'likeable_id' => $tweetId,
            'likeable_type' => Comment::class,
            'user_id' => $userId
        ]);
    }
}
