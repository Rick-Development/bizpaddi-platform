<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Modules\Communications\Models\Contact;
use App\Mail\SingleContactEmail;

class SendBulkContactEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ids;
    protected $subject;
    protected $message;

    /**
     * Create a new job instance.
     */
    public function __construct(array $ids, string $subject, string $message)
    {
        $this->ids = $ids;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $contacts = Contact::whereIn('id', $this->ids)->get();

        foreach ($contacts as $contact) {
            try {
                $personalizedMessage = $this->message;
                $personalizedSubject = $this->subject;
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

                foreach ($data as $key => $value) {
                    $pattern = '/\{\{\s*(&nbsp;)*' . $key . '(&nbsp;)*\s*\}\}/i';
                    $personalizedSubject = preg_replace($pattern, $value, $personalizedSubject);
                }

                Mail::to($contact->email)->send(new SingleContactEmail($personalizedSubject, $personalizedMessage));
            } catch (\Exception $e) {
                \Log::error("Failed to send bulk email to {$contact->email}: " . $e->getMessage());
            }
        }
    }
}
