<?php

declare(strict_types=1);

namespace App\Action\Common\CommentTweet;

use App\Entity\Like;
use App\Repository\LikeRepository;
use Illuminate\Support\Facades\Auth;

abstract class LikeItemAction
{
    protected $likeRepository;

    private const ADD_LIKE_STATUS = 'added';
    private const REMOVE_LIKE_STATUS = 'removed';

    public function __construct(LikeRepository $likeRepository)
    {
        $this->likeRepository = $likeRepository;
    }

    final public function execute(LikeItemRequestInterface $request): LikeItemResponse
    {
        $itemId = $this->getItemId($request->getItemId());

        $userId = Auth::id();

        // if user already liked tweet, we remove previous like
        if ($this->isExistLikeForItemByUser($itemId, $userId)) {
            $this->deleteForItemByUser($itemId, $userId);
            return new LikeItemResponse(self::REMOVE_LIKE_STATUS);
        }

        $like = $this->createLike($userId, $itemId);
        $this->likeRepository->save($like);

        return new LikeItemResponse(self::ADD_LIKE_STATUS);
    }

    abstract  protected function getItemId(int $id): int;
    abstract  protected function isExistLikeForItemByUser(int $itemId, int $userId): bool;
    abstract  protected function deleteForItemByUser(int $itemId, int $userId): void ;
    abstract  protected function createLike(int $userId, int $itemId): Like;
}
