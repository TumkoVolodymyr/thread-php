<?php

namespace App\Mail;

use App\Entity\Like;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LikeAddedEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Like
     */
    private $like;

    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    public function build()
    {
        $item = $this->like->likeable;
        return $this
            ->subject('You have new like')
            ->view('emails.like_added')
            ->with([
                'itemType' => $item->getLikeAddedEmailType(),
                'text' => $item->getLikeAddedEmailText()
            ]);
    }
}
