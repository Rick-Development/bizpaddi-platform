<x-settings::layouts.master>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">General Settings</h3>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('settings.store') }}" method="POST">
                            @csrf
                            
                            <h4>Help & Support</h4>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Support Email</label>
                                    <input type="email" name="support_email" class="form-control" value="{{ $settings['support_email'] }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Support Phone</label>
                                    <input type="text" name="support_phone" class="form-control" value="{{ $settings['support_phone'] }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">WhatsApp Number</label>
                                    <input type="text" name="support_whatsapp" class="form-control" value="{{ $settings['support_whatsapp'] }}">
                                </div>
                            </div>

                            <h4 class="mt-4">Social Media</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Facebook URL</label>
                                    <input type="url" name="social_facebook" class="form-control" value="{{ $settings['social_facebook'] }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Instagram URL</label>
                                    <input type="url" name="social_instagram" class="form-control" value="{{ $settings['social_instagram'] }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Twitter (X) URL</label>
                                    <input type="url" name="social_twitter" class="form-control" value="{{ $settings['social_twitter'] }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">LinkedIn URL</label>
                                    <input type="url" name="social_linkedin" class="form-control" value="{{ $settings['social_linkedin'] }}">
                                </div>
                            </div>

                            <h4 class="mt-4">Notifications (FCM)</h4>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">FCM Server Key</label>
                                    <textarea name="fcm_server_key" class="form-control" rows="3">{{ $settings['fcm_server_key'] }}</textarea>
                                    <small class="text-muted">Used for sending push notifications via Firebase Cloud Messaging.</small>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Save Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-settings::layouts.master>
