<?php

declare(strict_types=1);

namespace App\Http\Presenter;

use App\Entity\Like;
use Illuminate\Support\Collection;

final class LikeArrayPresenter implements CollectionAsArrayPresenter
{
    private $userPresenter;

    public function __construct(UserArrayPresenter $userPresenter)
    {
        $this->userPresenter = $userPresenter;
    }

    public function present(Like $like): array
    {
        return [
            'user_id' => $like->getUserId(),
            'author' => $this->userPresenter->present($like->getUser()),
            'created_at' => $like->getCreatedAt()->toDateTimeString()
        ];
    }

    public function presentCollection(Collection $collection): array
    {
        return $collection
            ->map(
                function (Like $like) {
                    return $this->present($like);
                }
            )
            ->all();
    }
}
