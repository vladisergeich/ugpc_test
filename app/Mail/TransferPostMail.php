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
    ) {}

    public function build(): self
    {
        return $this
            ->from('d.portal@danaflex.ru', 'UGPC-Portal')
            ->subject("Перемещение из"." ".$transfer->sender->name." "."в"." ".$transfer->recipient->name." ".".Номер перемещения"." ".$transfer->id)
            ->view('mail.transfer_posted')
            ->attachData(
                Excel::raw(new AppSteelExport($this->transfer), \Maatwebsite\Excel\Excel::XLSX),
                'Валы.xlsx'
            );
    }
}