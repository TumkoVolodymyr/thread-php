<?php

namespace App\Listeners;

use App\Events\LikeAdded;
use App\Mail\LikeAddedEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class SendLikeAddedNotification implements ShouldQueue
{
    use SerializesModels;

    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(LikeAdded $event): void
    {
        $like = $event->getLike();
        $item = $like->likeable;
        if ($like->user_id === $item->author_id){
            return;
        }
        $resourceEmail = $item->author->email;
        $this->mailer->to($resourceEmail)->send(new LikeAddedEmail($like));
    }
}
