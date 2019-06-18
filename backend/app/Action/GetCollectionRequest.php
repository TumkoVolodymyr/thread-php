<?php

declare(strict_types=1);

namespace App\Action;

class GetCollectionRequest
{
    private $page;
    private $sort;
    private $direction;
    private $isLiked;

    public function __construct(?int $page, ?string $sort, ?string $direction, ?bool $isLiked)
    {
        $this->page = $page;
        $this->sort = $sort;
        $this->direction = $direction;
        $this->isLiked = $isLiked;
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getSort(): ?string
    {
        return $this->sort;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function getIsLiked(): ?bool
    {
        return $this->isLiked;
    }
}
