<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Models\SteelShaftApplication;
use App\Models\Transfer;
use App\Models\Mailing;
use App\Mail\AppSteelExcelFileMail;
use App\Mail\TransferConfirmMail;
use App\Mail\TransferPostMail;

class NotificationService
{

    public function sendSteelApp(SteelShaftApplication $steelApp): void
    {
        $steelApp->load(['shafts.shaft', 'engraver']);
        $vendorIds = (array) $steelApp->vendor_id;

        $mailings = Mailing::with([
            'recipients' => fn($q) => $q->whereIn('supplier_id', $vendorIds),
            'recipients.user',
        ])
        ->where('type', 'shaft_create')
        ->whereHas('recipients', fn($q) => $q->whereIn('supplier_id', $vendorIds))
        ->get();

        $files = collect($steelApp->attachments)->pluck('path')->all();

        foreach ($mailings as $mailing) {
            foreach ($mailing->recipients as $recipient) {
                $email = optional($recipient->user)->email;
                if ($email) {
                    Mail::to($email)->queue(new AppSteelExcelFileMail($steelApp, $files));
                }
            }
        }

        $steelApp->update(['status' => 'completed']);
    }

    public function confirmTransfer(Transfer $transfer): void
    {
        $transfer->load(['shafts.shaft']);

        $mailings = Mailing::with([
            'recipients' => fn($q) => $q->where('from_warehouse_id', $transfer->sender_id)->where('to_warehouse_id', $transfer->recipient_id),
            'recipients.user',
        ])
        ->where('type', 'confirm_transfer')
        ->whereHas('recipients', fn($q) => $q->where('from_warehouse_id', $transfer->sender_id)->where('to_warehouse_id', $transfer->recipient_id))
        ->get();

        foreach ($mailings as $email) {
            Mail::to($email)->queue(new TransferConfirmMail($transfer));
        }
    }

    public function postTransfer(Transfer $transfer): void
    {
        $transfer->load(['shafts.shaft']);

        $emails = Mailing::with([
            'recipients' => fn($q) => $q->where('from_warehouse_id', $transfer->sender_id)->where('to_warehouse_id', $transfer->recipient_id),
            'recipients.user',
        ])
        ->where('type', 'post_transfer')
        ->whereHas('recipients', fn($q) => $q->where('from_warehouse_id', $transfer->sender_id)->where('to_warehouse_id', $transfer->recipient_id))
        ->pluck('recipients.user.email')->filter()->unique()->values();

        $to  = $emails->first();
        $cc  = $emails->slice(1);
        
        if ($to) {
            Mail::to($to)->cc($cc)->send(new TransferPostMail($transfer));
        }
    }

    protected function getRecipients($vendorOrSender, $recipient = null, string $type): array
    {
        return Mailing::with(['recipients.user'])
            ->where('type', $type)
            ->when($recipient, fn($q) => $q->where('sender', $vendorOrSender)->where('recipient', $recipient))
            ->when(!$recipient, fn($q) => $q->whereHas('recipients', fn($r) => $r->whereIn('supplier_id', (array) $vendorOrSender)))
            ->get()
            ->flatMap(fn($m) => $m->recipients->pluck('user.email'))
            ->filter()
            ->unique()
            ->toArray();
    }
}
