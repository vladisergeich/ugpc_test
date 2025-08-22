<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AppSteelExport;
use App\Models\SteelShaftApplication;
use App\Mail\AppSteelExcelFileMail;

class AppSteelExcelFileMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public SteelShaftApplication $application,
        public array $filePaths = []
    ) {}

    public function build(): self
    {
        $email = $this
            ->from('d.portal@danaflex.ru', 'UGPC-Portal')
            ->subject('Заявка на изготовление стальных валов № ' . $this->application->id)
            ->view('mail.mail_app_steel')
            ->attachData(
                Excel::raw(new AppSteelExport($this->application), \Maatwebsite\Excel\Excel::XLSX),
                'Валы.xlsx'
            );

        foreach ($this->filePaths as $path) {
            $realPath = storage_path('app/public/' . $path);
            if (file_exists($realPath)) {
                $email->attach($realPath, ['as' => basename($path)]);
            }
        }

        return $email;
    }
}

