<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tweet;
use App\Exceptions\TweetNotFoundException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

final class TweetRepository implements Paginable
{
    public function create(array $fields): Tweet
    {
        return Tweet::create($fields);
    }

    public function paginate(
        int $page = self::DEFAULT_PAGE,
        int $perPage = self::DEFAULT_PER_PAGE,
        string $sort = self::DEFAULT_SORT,
        string $direction = self::DEFAULT_DIRECTION,
        bool $isLiked = false
    ): LengthAwarePaginator {
        $qb = Tweet::orderBy($sort, $direction);
        if ($isLiked){
            $qb->leftJoin('likes', function($join) {
                $join->on('tweets.id', '=', 'likes.likeable_id');
            })
                ->whereNotNull('likes.user_id')
                ->where('likes.user_id', Auth::id())
                ->where('likes.likeable_type', Tweet::class)
                ->groupBy('tweets.id');
        }
        return $qb->paginate($perPage, ['*'], null, $page);
    }

    public function commentedByUser(
        int $currentUserId,
        int $page = self::DEFAULT_PAGE,
        int $perPage = self::DEFAULT_PER_PAGE,
        string $sort = self::DEFAULT_SORT,
        string $direction = self::DEFAULT_DIRECTION,
        bool $isLiked = false
    ): LengthAwarePaginator {
        $qb = Tweet::leftJoin('comments', function($join) {
            $join->on('tweets.id', '=', 'comments.tweet_id');
        })
            ->whereNotNull('comments.author_id')
            ->where('comments.author_id', $currentUserId)
            ->groupBy('tweets.id')
            ->orderBy($sort, $direction);

        if ($isLiked){
            $qb->leftJoin('likes', function($join) {
                $join->on('tweets.id', '=', 'likes.likeable_id');
            })
                ->whereNotNull('likes.user_id')
                ->where('likes.user_id', Auth::id())
                ->where('likes.likeable_type', Tweet::class);
        };
        $qb->paginate($perPage, ['*'], null, $page);
        return    $qb->paginate($perPage, ['*'], null, $page);
    }

    /**
     * @param int $id
     * @return Tweet
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Tweet
    {
        return Tweet::findOrFail($id);
    }

    /**
     * @param int $id
     * @return Tweet
     * @throws ModelNotFoundException
     */
    public function getByIdWithCurrentUserCommentsCount(int $id): ?Tweet
    {
        $tweets =  Tweet::where('id', $id)
            ->selectSub(function ($query) use ($id) {
            $query->from('comments')
                ->selectRaw('COUNT(comments.id)')
                ->where('comments.tweet_id', $id )
                ->where('comments.author_id', Auth::id());

        }, 'user_comment_count')->get();

        $tweet = ($tweets->first());
        if (!($tweet instanceof Tweet)){
            throw new TweetNotFoundException();
        }
        return $tweet;
    }

    public function getPaginatedByUserId(
        int $userId,
        int $page = self::DEFAULT_PAGE,
        int $perPage = self::DEFAULT_PER_PAGE,
        string $sort = self::DEFAULT_SORT,
        string $direction = self::DEFAULT_DIRECTION
    ): LengthAwarePaginator {
        return Tweet::where('author_id', $userId)
            ->orderBy($sort, $direction)
            ->paginate($perPage, ['*'], null, $page);
    }

    public function getPaginatedByUserIdAndCommentedByUser(
        int $currentUserId,
        int $userId,
        int $page = self::DEFAULT_PAGE,
        int $perPage = self::DEFAULT_PER_PAGE,
        string $sort = self::DEFAULT_SORT,
        string $direction = self::DEFAULT_DIRECTION
    ): LengthAwarePaginator {
        return Tweet::leftJoin('comments', function($join) {
            $join->on('tweets.id', '=', 'comments.tweet_id');
        })
            ->whereNotNull('comments.author_id')
            ->where('comments.author_id', $currentUserId)
            ->groupBy('tweets.id')
            ->where('tweets.author_id', $userId)
            ->orderBy($sort, $direction)
            ->paginate($perPage, ['*'], null, $page);
    }

    public function save(Tweet $tweet): Tweet
    {
        $tweet->save();

        return $tweet;
    }

    public function delete(Tweet $tweet): ?bool
    {
        return $tweet->delete();
    }
}
