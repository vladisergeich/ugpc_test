<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Exports\AppSteelExport;
use Maatwebsite\Excel\Facades\Excel;

class AppSteelExcelFileMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tempExcelFilePath;
    public $otherFilePaths;
    public $documentNumber;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tempExcelFilePath, $otherFilePaths, $documentNumber)
    {
        $this->tempExcelFilePath = $tempExcelFilePath;
        $this->otherFilePaths = $otherFilePaths;
        $this->documentNumber = $documentNumber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $attachments = [];

        foreach ($this->otherFilePaths as $filePath) {
            $temporaryFilePath = tempnam(sys_get_temp_dir(), 'temp_file');
            copy(storage_path('app/public/' . $filePath), $temporaryFilePath);
            $attachments[] = [
                'file' => $temporaryFilePath,
                'options' => [
                    'as' => basename($filePath), 
                ],
            ];
        }

        foreach ($attachments as $attachment) {
            $this->attach($attachment['file'], ['as' => $attachment['options']['as']]);
        }

        return $this->view('ugpc.mail_app_steel') // Замените 'ugpc.mail_app_steel' на ваш шаблон
                    ->subject("Заявка на изготовление стальных валов № " . $this->documentNumber)
                    ->from('d.portal@danaflex.ru', 'UGPC-Portal')
                    ->attachData(Excel::raw(new AppSteelExport($this->documentNumber), \Maatwebsite\Excel\Excel::XLSX), 'Валы.xlsx');
    }
}
