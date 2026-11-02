<?php
namespace Modules\Legal\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\Legal\Models\LegalPage;

class LegalSeeder extends Seeder {
    public function run(): void {
        LegalPage::updateOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'title' => 'Privacy Policy',
                'content' => '<h1>Privacy Policy</h1><p>Effective Date: November 26, 2025 | Last Updated: November 25, 2025</p><p>Bizpaddi (“Bizpaddi,” “we,” “our,” or “us”) is committed to safeguarding the privacy, confidentiality, and integrity of personal and business information entrusted to us. This Privacy Policy outlines how we collect, use, store, disclose, and protect data across our digital platforms, services, and client engagements.</p><h3>1. Data Collection</h3><p>We collect information necessary to deliver secure, efficient, and high-quality digital services, including Personal Identification Data, Account & Client Data, Payment Information, and Technical Data.</p>',
                'version' => 1,
                'effective_date' => now(),
            ]
        );
        LegalPage::updateOrCreate(
            ['slug' => 'terms-conditions'],
            [
                'title' => 'Terms & Conditions',
                'content' => '<h1>Terms & Conditions</h1><p>Effective Date: December 16, 2025 | Last Updated: December 16, 2025</p><p>These Terms & Conditions (“Terms”) govern the use of all services, platforms, websites, software products, and engagements provided by Bizpaddi. By accessing or engaging our services, clients agree to be legally bound by these Terms.</p>',
                'version' => 1,
                'effective_date' => now(),
            ]
        );
        LegalPage::updateOrCreate(
            ['slug' => 'limitation-of-liability'],
            [
                'title' => 'Limitation of Liability',
                'content' => '<h1>Limitation of Liability</h1><p>Effective Date: December 21, 2025 | Last Updated: December 12, 2025</p><p>Bizpaddi shall not be liable for indirect, incidental, consequential, or economic losses. Total liability, if any, shall not exceed the total amount paid for the specific service giving rise to the claim.</p>',
                'version' => 1,
                'effective_date' => now(),
            ]
        );
        LegalPage::updateOrCreate(
            ['slug' => 'cookies'],
            [
                'title' => 'Cookie Policy',
                'content' => '<h1>Cookie Policy</h1><p>We use cookies and similar technologies to enhance functionality, analyze usage, and improve service delivery. Users may control cookie preferences through their browser settings.</p>',
                'version' => 1,
                'effective_date' => now(),
            ]
        );
    }
}
