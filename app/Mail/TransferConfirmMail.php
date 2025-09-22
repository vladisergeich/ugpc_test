<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Transfer;

class ShaftRequestConfirmed extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Transfer $transfer,
        public int $count
    ) {}

    public function build(): self
    {
        return $this
            ->from('d.portal@danaflex.ru', 'UGPC-Portal')
            ->subject('Заявка на перемещение валов глубокой печати')
            ->view('mail.transfer_confirmed');
    }
}