<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $blade;
    protected $data;
    protected $mail;
    protected $title;

    public function __construct(String $blade, array $data = [], String $mail, String $title = 'TsunagunFP mail!') {
        $this->blade = $blade;
        $this->data = $data;
        $this->mail = $mail;
        $this->title = $title;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $adminMail = 'admin.test@gmail.com';
        Mail::send($this->blade, $this->data, function ($message) use ($adminMail) {
            $message->from($adminMail);
            $message->to($this->mail)->subject($this->title);
        });
    }
}
