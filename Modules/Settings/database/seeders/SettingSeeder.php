<?php
namespace Modules\Settings\Database\Seeders;
use Illuminate\Database\Seeder;
use Modules\Settings\Models\Setting;

class SettingSeeder extends Seeder {
    public function run(): void {
        Setting::updateOrCreate(['key' => 'site_name'], ['value' => 'Bizpaddi', 'type' => 'string', 'description' => 'Website name']);
        Setting::updateOrCreate(['key' => 'contact_email'], ['value' => 'hello@bizpaddi.com', 'type' => 'string', 'description' => 'Primary contact email']);
        Setting::updateOrCreate(['key' => 'suspended_message'], [
            'value' => '<strong>Your account has been suspended.</strong><br><br>This may be due to a violation of our terms of service or a pending administrative review. Please contact the HR department or your supervisor for more details.',
            'type' => 'string',
            'description' => 'Message shown to suspended team members'
        ]);
        Setting::updateOrCreate(['key' => 'inactive_message'], [
            'value' => '<strong>Your account is currently inactive.</strong><br><br>Access to the Team Portal is restricted for inactive or terminated accounts. If you believe this is an error, please reach out to support.',
            'type' => 'string',
            'description' => 'Message shown to inactive team members'
        ]);
    }
}
