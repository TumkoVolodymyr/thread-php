<?php

namespace App\Events;

use App\Entity\Like;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class LikeAdded
{
    use Dispatchable, SerializesModels;

    private $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    public function getLike(): Like
    {
        return $this->like;
    }
}
