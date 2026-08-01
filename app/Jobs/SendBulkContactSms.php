<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Communications\Models\Contact;
use App\Services\SmsService;

class SendBulkContactSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ids;
    protected $message;

    /**
     * Create a new job instance.
     */
    public function __construct(array $ids, string $message)
    {
        $this->ids = $ids;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(SmsService $smsService): void
    {
        $contacts = Contact::whereIn('id', $this->ids)->whereNotNull('phone')->get();

        foreach ($contacts as $contact) {
            try {
                $personalizedMessage = $this->message;
                $data = [
                    'first_name' => $contact->first_name ?? '',
                    'last_name' => $contact->last_name ?? '',
                    'email' => $contact->email,
                    'company' => $contact->company ?? '',
                    'phone' => $contact->phone ?? '',
                ];
                
                foreach ($data as $key => $value) {
                    $pattern = '/\{\{\s*(&nbsp;)*' . $key . '(&nbsp;)*\s*\}\}/i';
                    $personalizedMessage = preg_replace($pattern, $value, $personalizedMessage);
                }

                $smsService->send($contact->phone, $personalizedMessage);
            } catch (\Exception $e) {
                \Log::error("Failed to send bulk SMS to {$contact->phone}: " . $e->getMessage());
            }
        }
    }
}
