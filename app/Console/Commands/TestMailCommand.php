<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'mail:test {email : The recipient email address}';

    /**
     * The console command description.
     */
    protected $description = 'Send a test email using current Laravel SMTP configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recipient = $this->argument('email');

        $this->info("=== Current Mail Configuration ===");
        $this->line("Mailer:    " . Config::get('mail.default'));
        $this->line("Host:      " . Config::get('mail.mailers.smtp.host'));
        $this->line("Port:      " . Config::get('mail.mailers.smtp.port'));
        $this->line("Scheme:    " . (Config::get('mail.mailers.smtp.scheme') ?: 'none/auto'));
        $this->line("Username:  " . Config::get('mail.mailers.smtp.username'));
        $this->line("Password:  " . (Config::get('mail.mailers.smtp.password') ? '****** (configured)' : '(empty)'));
        $this->line("From:      " . Config::get('mail.from.address') . ' (' . Config::get('mail.from.name') . ')');
        $this->line("Recipient: " . $recipient);
        $this->newLine();

        $this->info("Attempting to connect and send test message...");

        try {
            Mail::raw("Hello! This is a test email sent from Bizpaddi on " . date('Y-m-d H:i:s') . ".", function ($msg) use ($recipient) {
                $msg->to($recipient)->subject("Bizpaddi SMTP Diagnostic Test");
            });

            $this->info(" SUCCESS: Test email was accepted by the SMTP server for delivery to {$recipient}!");
            return 0;
        } catch (\Throwable $e) {
            $this->error(" FAILED: " . $e->getMessage());
            $this->newLine();
            $this->line("File: " . $e->getFile() . ':' . $e->getLine());
            $this->newLine();
            $this->line("Suggestions for Webuzo / cPanel mail hosting:");
            $this->line("1. If mail.bizpaddi.com is proxied by Cloudflare, Cloudflare blocks port 587/465. Use MAIL_HOST=127.0.0.1 or localhost.");
            $this->line("2. Check if your mail server uses port 465 (MAIL_SCHEME=smtps) instead of 587 (MAIL_SCHEME=smtp).");
            $this->line("3. Verify that the email account noreply@bizpaddi.com exists in Webuzo Email Accounts with the exact password.");
            return 1;
        }
    }
}

