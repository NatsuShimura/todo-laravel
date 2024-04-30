<?php

namespace App\Mail;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

//〈９章〉メールの設定
class CommentPosted extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $comment;

    public function __construct(User $user, Comment $comment)
    {
        $this->user = $user;
        $this->comment = $comment;
    }

    //メソッドを組み合わせてメールの内容を作成
    public function build()
    {
        //$this：件名が設定
        return $this
            ->subject('コメントありがとうございます')
            ->view('emails.comments.posted');
    }
}