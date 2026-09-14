<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Communications\Models\EmailTemplate;

class OperationalMailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $templateName;
    public array $placeholders;
    public array $attachments;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $templateName, array $placeholders = [], array $attachments = [])
    {
        $this->templateName = $templateName;
        $this->placeholders = $placeholders;
        $this->attachments = $attachments;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $template = EmailTemplate::where('name', $this->templateName)->first();

        // Safe Fallback: if template is missing or inactive, send standard MailMessage
        if (!$template || !$template->is_active) {
            $fallbackSubject = ucwords(str_replace('_', ' ', $this->templateName));
            $fallbackMail = (new MailMessage)
                ->subject($fallbackSubject)
                ->greeting('Hello,')
                ->line('This is an operational notification from ' . config('app.name') . '.');

            foreach ($this->placeholders as $key => $value) {
                if (is_scalar($value)) {
                    $fallbackMail->line(ucwords(str_replace('_', ' ', $key)) . ': ' . $value);
                }
            }

            return $fallbackMail;
        }

        // Populate global placeholders
        $mergedPlaceholders = array_merge([
            'site_name' => config('app.name'),
            'site_url' => config('app.url'),
            'name' => $notifiable->name ?? 'Team Member',
        ], $this->placeholders);

        // Replace placeholders in subject
        $subject = $template->subject;
        foreach ($mergedPlaceholders as $key => $value) {
            if (is_scalar($value)) {
                $subject = str_replace(
                    ['{{' . $key . '}}', '{{ ' . $key . ' }}'],
                    (string) $value,
                    $subject
                );
            }
        }

        // Replace placeholders in content body
        $body = $template->replaceVariables($mergedPlaceholders);

        // Render mail inside standard layout wrapper with plain text alternative for high email deliverability
        $email = (new MailMessage)
            ->subject($subject)
            ->view('emails.single-contact', [
                'contentBody' => $body,
                'subject' => $subject,
            ])
            ->text('emails.single-contact-text', [
                'contentBody' => $body,
                'subject' => $subject,
            ]);

        // Attach any files provided
        if (!empty($this->attachments)) {
            foreach ($this->attachments as $filePath => $options) {
                $email->attach($filePath, $options);
            }
        }

        return $email;
    }
}
