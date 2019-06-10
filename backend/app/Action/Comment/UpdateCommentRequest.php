<?php

declare(strict_types=1);

namespace App\Action\Comment;

final class UpdateCommentRequest
{
    private $body;
    private $id;

    public function __construct(int $id, string $body)
    {
        $this->body = $body;
        $this->id = $id;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
