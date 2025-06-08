<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ContactSubmission;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContactExport;

class ContactController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ContactSubmission::query();

        // Search theo tên hoặc email
        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->keyword . '%')
                    ->orWhere('email', 'like', '%' . $request->keyword . '%');
            });
        }

        // Filter theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contacts = $query->orderByDesc('created_at')->paginate(10)->appends($request->all());
        return view('admin.contacts.index', compact('contacts'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Tìm sản phẩm theo ID
        $contacts = ContactSubmission::findOrFail($id);

        return view('admin.contacts.edit', compact('contacts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'subject' => 'nullable|string',
            'message' => 'nullable|string',
            'status' => 'required|string',
            'admin_reply' => 'nullable|string',
            'replied_by' => 'nullable|exists:users,id',
        ]);

        $contact = ContactSubmission::findOrFail($id);
        $contact->email = $request->email;
        $contact->name = $request->name;
        $contact->phone = $request->phone;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->status = $request->status;
        $contact->admin_reply = $request->admin_reply;
        $contact->replied_by = $request->replied_by;
        $contact->save();

        return redirect()->route('admin.contacts.index')->with('success', 'Cập nhật liên hệ thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = ContactSubmission::findOrFail($id);
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Contact deleted successfully!');
    }

    public function export()
    {
        $contacts = ContactSubmission::all();

        $filename = 'contacts_' . date('Ymd_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 'Status', 'Admin Reply', 'Replied By', 'Created At'];

        $callback = function () use ($contacts, $columns) {
            // Xóa output buffer nếu có
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            $file = fopen('php://output', 'w');
            // Ghi header
            fputcsv($file, $columns);

            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->id,
                    $contact->name,
                    $contact->email,
                    $contact->phone,
                    $contact->subject,
                    $contact->message,
                    $contact->status,
                    $contact->admin_reply,
                    $contact->replied_by,
                    $contact->created_at,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
