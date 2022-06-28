<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Post;

class PostUpdate extends Mailable
{
    use Queueable, SerializesModels;
    
    public $post;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.markdown.post_update')
        ->with([
            'post_slug' => $this->post->slug,
            'post_url' => env('APP_URL') . '/admin/posts/' . $this->post->slug,
        ]);
    }
}
