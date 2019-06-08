<?php

declare(strict_types=1);

namespace App\Action\Comment;

use App\Action\Common\CommentTweet\LikeItemAction;
use App\Entity\Like;
use App\Repository\LikeRepository;
use App\Repository\CommentRepository;

final class LikeCommentAction extends LikeItemAction
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository, LikeRepository $likeRepository)
    {
        parent::__construct($likeRepository);;
        $this->commentRepository = $commentRepository;
    }

    protected function getItemId(int $id): int
    {
        return $this->commentRepository->getById($id)->id;
    }

    protected function isExistLikeForItemByUser(int $itemId, int $userId): bool
    {
        return $this->likeRepository->existsForCommentByUser($itemId, $userId);
    }

    protected function deleteForItemByUser(int $itemId, int $userId): void
    {
        $this->likeRepository->deleteForCommentByUser($itemId, $userId);
    }

    protected function createLike(int $userId, int $itemId): Like
    {
        $like = new Like();
        $like->forComment($userId, $itemId);
        return $like;
    }

}
