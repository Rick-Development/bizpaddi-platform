<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Communications\Models\Contact;
use Modules\Communications\Models\ContactList;
use Modules\Communications\Models\EmailTemplate;
use Modules\Communications\Models\SmsTemplate;
use App\Services\SmsService;
use Illuminate\Http\Request;
use App\Jobs\SendBulkContactEmail;
use App\Jobs\SendBulkContactSms;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\SingleContactEmail;
use Barryvdh\DomPDF\Facade\Pdf;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contacts = Contact::query()
            ->when($request->search, function($query, $search) {
                $query->where('email', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('company', 'like', "%{$search}%");
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(25);

        return view('admin::contacts.index', compact('contacts'));
    }

    public function create()
    {
        $lists = ContactList::where('is_active', true)->get();
        return view('admin::contacts.create', compact('lists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'nullable|email|unique:contacts,email|max:255',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'status' => 'required|in:active,unsubscribed,bounced',
            'list_ids' => 'nullable|array',
            'list_ids.*' => 'exists:contact_lists,id',
        ]);

        $listIds = $validated['list_ids'] ?? [];
        unset($validated['list_ids']);

        $validated['subscribed_at'] = $validated['status'] === 'active' ? now() : null;

        $contact = Contact::create($validated);

        if (!empty($listIds)) {
            $contact->lists()->attach($listIds);
            foreach ($listIds as $listId) {
                ContactList::find($listId)?->updateContactCount();
            }
        }

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact created successfully.');
    }

    public function edit(Contact $contact)
    {
        $lists = ContactList::where('is_active', true)->get();
        $contact->load('lists');
        
        return view('admin::contacts.edit', compact('contact', 'lists'));
    }

    public function update(Request $request, Contact $contact)
    {
        $validated = $request->validate([
            'email' => 'nullable|email|max:255|unique:contacts,email,' . $contact->id,
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'status' => 'required|in:active,unsubscribed,bounced',
            'list_ids' => 'nullable|array',
            'list_ids.*' => 'exists:contact_lists,id',
        ]);

        $listIds = $validated['list_ids'] ?? [];
        unset($validated['list_ids']);

        $contact->update($validated);

        $oldListIds = $contact->lists()->pluck('contact_lists.id')->toArray();
        $contact->lists()->sync($listIds);

        $affectedListIds = array_unique(array_merge($oldListIds, $listIds));
        foreach ($affectedListIds as $listId) {
            ContactList::find($listId)?->updateContactCount();
        }

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        $listIds = $contact->lists()->pluck('contact_lists.id')->toArray();
        $contact->delete();

        foreach ($listIds as $listId) {
            ContactList::find($listId)?->updateContactCount();
        }

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }

    /**
     * Export contacts as CSV
     */
    public function export(Request $request)
    {
        $contacts = Contact::query()
            ->when($request->search, function($query, $search) {
                $query->where('email', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('company', 'like', "%{$search}%");
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->list_id, function($query, $listId) {
                $query->whereHas('lists', function($q) use ($listId) {
                    $q->where('contact_lists.id', $listId);
                });
            })
            ->latest()
            ->get();

        $filename = 'contacts_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($contacts) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, ['First Name', 'Last Name', 'Email', 'Phone', 'Company', 'Role/Designation', 'Status', 'Date Added']);

            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->first_name ?? '',
                    $contact->last_name ?? '',
                    $contact->email ?? '',
                    $contact->phone ?? '',
                    $contact->company ?? '',
                    $contact->role ?? '',
                    $contact->status,
                    $contact->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export contacts as PDF with watermark
     */
    public function exportPdf(Request $request)
    {
        $contacts = Contact::query()
            ->when($request->search, function($query, $search) {
                $query->where('email', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('company', 'like', "%{$search}%");
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->list_id, function($query, $listId) {
                $query->whereHas('lists', function($q) use ($listId) {
                    $q->where('contact_lists.id', $listId);
                });
            })
            ->latest()
            ->get();

        $settings = DB::table('settings')
            ->whereIn('key', ['watermark_logo', 'company_logo', 'site_name'])
            ->pluck('value', 'key');

        // Convert to absolute paths for dompdf
        $watermarkLogo = $settings['watermark_logo']
            ? 'data:image/jpeg;base64,' . base64_encode(Storage::get($settings['watermark_logo']))
            : null;
        $companyLogo = $settings['company_logo']
            ? 'data:image/jpeg;base64,' . base64_encode(Storage::get($settings['company_logo']))
            : null;
        $siteName = $settings['site_name'] ?? config('app.name');

        $pdf = Pdf::loadView('admin::contacts.export-pdf', compact('contacts', 'watermarkLogo', 'companyLogo', 'siteName'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('contacts_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Import contacts from CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
            'list_id' => 'nullable|exists:contact_lists,id',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        // Skip header row
        $header = fgetcsv($handle);
        if (!$header) {
            return back()->with('error', 'The CSV file appears to be empty.');
        }

        // Normalize headers
        $header = array_map(fn($h) => strtolower(trim(str_replace([' ', '-'], '_', $h))), $header);

        $imported = 0;
        $skipped = 0;
        $listId = $request->list_id;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) < 1) continue;

            $data = array_combine(array_slice($header, 0, count($row)), array_slice($row, 0, count($header)));

            $email = trim($data['email'] ?? $data['email_address'] ?? '');
            $phone = trim($data['phone'] ?? $data['phone_number'] ?? $data['mobile'] ?? '');

            // Skip if no email and no phone
            if (!$email && !$phone) {
                $skipped++;
                continue;
            }

            // Skip duplicate email
            if ($email && Contact::where('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            $contact = Contact::create([
                'first_name' => trim($data['first_name'] ?? ''),
                'last_name'  => trim($data['last_name'] ?? $data['surname'] ?? ''),
                'email'      => $email ?: null,
                'phone'      => $phone ?: null,
                'company'    => trim($data['company'] ?? $data['company_name'] ?? ''),
                'role'       => trim($data['role'] ?? $data['designation'] ?? $data['job_title'] ?? ''),
                'status'     => 'active',
                'subscribed_at' => now(),
            ]);

            if ($listId) {
                $contact->lists()->attach($listId);
            }

            $imported++;
        }

        fclose($handle);

        if ($listId) {
            ContactList::find($listId)?->updateContactCount();
        }

        return back()->with('success', "Import complete: {$imported} contacts added, {$skipped} skipped (duplicates or empty rows).");
    }

    public function sendEmail(Contact $contact)
    {
        $templates = EmailTemplate::where('is_active', true)->get();
        return view('admin::contacts.send-email', compact('contact', 'templates'));
    }

    public function processEmail(Request $request, Contact $contact)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if (!$contact->email) {
            return back()->with('error', 'This contact does not have an email address.');
        }

        try {
            $subject = $request->subject;
            $message = $request->message;
            $data = [
                'first_name' => $contact->first_name ?? '',
                'last_name' => $contact->last_name ?? '',
                'email' => $contact->email,
                'company' => $contact->company ?? '',
                'phone' => $contact->phone ?? '',
            ];
            
            foreach ($data as $key => $value) {
                $pattern = '/\{\{\s*(&nbsp;)*' . $key . '(&nbsp;)*\s*\}\}/i';
                $message = preg_replace($pattern, $value, $message);
            }

            foreach ($data as $key => $value) {
                $pattern = '/\{\{\s*(&nbsp;)*' . $key . '(&nbsp;)*\s*\}\}/i';
                $subject = preg_replace($pattern, $value, $subject);
            }
            
            Mail::to($contact->email)->send(new SingleContactEmail($subject, $message));
            
            return redirect()->route('admin.contacts.index')
                ->with('success', 'Email sent to ' . $contact->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function sendSms(Contact $contact)
    {
        $templates = SmsTemplate::where('is_active', true)->get();
        return view('admin::contacts.send-sms', compact('contact', 'templates'));
    }

    public function processSms(Request $request, Contact $contact, SmsService $smsService)
    {
        $request->validate([
            'message' => 'required|string|max:160',
        ]);

        if (!$contact->phone) {
            return back()->with('error', 'This contact does not have a phone number.');
        }

        try {
            $message = $request->message;
            $data = [
                'first_name' => $contact->first_name ?? '',
                'last_name' => $contact->last_name ?? '',
                'email' => $contact->email ?? '',
                'company' => $contact->company ?? '',
                'phone' => $contact->phone ?? '',
            ];
            
            foreach ($data as $key => $value) {
                $pattern = '/\{\{\s*(&nbsp;)*' . $key . '(&nbsp;)*\s*\}\}/i';
                $message = preg_replace($pattern, $value, $message);
            }

            $result = $smsService->send($contact->phone, $message);

            if (!$result['success']) {
                throw new \Exception($result['error']);
            }
            
            return redirect()->route('admin.contacts.index')
                ->with('success', 'SMS sent to ' . $contact->phone);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send SMS: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (empty($ids)) {
            return back()->with('error', 'No contacts selected.');
        }

        Contact::whereIn('id', $ids)->delete();

        return redirect()->route('admin.contacts.index')
            ->with('success', count($ids) . ' contacts deleted successfully.');
    }

    public function bulkSendEmail(Request $request)
    {
        $ids = [];
        
        if ($request->list_id) {
            $list = ContactList::findOrFail($request->list_id);
            $ids = $list->contacts()->pluck('contacts.id')->toArray();
        } elseif ($request->ids) {
            $ids = explode(',', $request->ids);
        }

        if (empty($ids)) {
            return redirect()->route('admin.contacts.index')->with('error', 'No contacts selected.');
        }

        $contacts = Contact::whereIn('id', $ids)->whereNotNull('email')->get();
        $templates = EmailTemplate::where('is_active', true)->get();

        return view('admin::contacts.bulk-send-email', compact('contacts', 'templates'));
    }

    public function processBulkEmail(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $ids = explode(',', $request->ids);
        
        SendBulkContactEmail::dispatch($ids, $request->subject, $request->message);

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Bulk email job has been dispatched. Emails will be sent in the background.');
    }

    public function bulkSendSms(Request $request)
    {
        $ids = [];

        if ($request->list_id) {
            $list = ContactList::findOrFail($request->list_id);
            $ids = $list->contacts()->pluck('contacts.id')->toArray();
        } elseif ($request->ids) {
            $ids = explode(',', $request->ids);
        }

        if (empty($ids)) {
            return redirect()->route('admin.contacts.index')->with('error', 'No contacts selected.');
        }

        $contacts = Contact::whereIn('id', $ids)->whereNotNull('phone')->get();
        $templates = SmsTemplate::where('is_active', true)->get();

        return view('admin::contacts.bulk-send-sms', compact('contacts', 'templates'));
    }

    public function processBulkSms(Request $request)
    {
        $request->validate([
            'ids' => 'required|string',
            'message' => 'required|string|max:160',
        ]);

        $ids = explode(',', $request->ids);
        
        SendBulkContactSms::dispatch($ids, $request->message);
        
        return redirect()->route('admin.contacts.index')
            ->with('success', 'Bulk SMS job has been dispatched. Messages will be sent in the background.');
    }
}
