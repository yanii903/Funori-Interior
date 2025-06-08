@extends('admin.layout.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-30">
                <h3>Edit Contact</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <a href="{{ route('admin.contacts.index') }}">
                            <div class="text-tiny">Contact</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <div class="text-tiny">Edit Contact</div>
                    </li>
                </ul>
            </div>
            <!-- form-edit-contact -->
            <form class="form-edit-contact" method="POST" action="{{ route('admin.contacts.update', $contacts->id) }}">
                @csrf
                @method('PUT')
                <div class="wg-box mb-30">
                    <fieldset class="email">
                        <div class="body-title mb-10">Email</div>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $contacts->email) }}" readonly>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Name</div>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $contacts->name) }}" readonly>
                    </fieldset>
                    <fieldset class="phone">
                        <div class="body-title mb-10">Phone</div>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $contacts->phone) }}" readonly>
                    </fieldset>
                    <fieldset class="subject">
                        <div class="body-title mb-10">Subject</div>
                        <input type="text" class="form-control" name="subject" value="{{ old('subject', $contacts->subject) }}" readonly>
                    </fieldset>
                    <fieldset class="message">
                        <div class="body-title mb-10">Message</div>
                        <input type="text" name="message" class="form-control" value="{{ old('message', $contacts->message) }}" readonly></input>
                    </fieldset>
                    <fieldset class="status">
                        <div class="body-title mb-10">Status</div>
                        <select name="status">
                            <option value="new" {{ $contacts->status == 'new' ? 'selected' : '' }}>New</option>
                            <option value="read" {{ $contacts->status == 'read' ? 'selected' : '' }}>Read</option>
                            <option value="replied" {{ $contacts->status == 'replied' ? 'selected' : '' }}>Replied</option>
                            <option value="resolved" {{ $contacts->status == 'resolved' ? 'selected' : '' }}>Resolved
                            </option>
                        </select>
                    </fieldset>
                    <fieldset class="admin_reply">
                        <div class="body-title mb-10">Admin Reply</div>
                        <textarea name="admin_reply" class="card rounded-3 border-2" rows="3">{{ old('admin_reply', $contacts->admin_reply) }}</textarea>
                    </fieldset>
                    @php
                        $admins = \App\Models\User::where('role', 'admin')->get();
                    @endphp
                    <fieldset class="replied_by">
                        <div class="body-title mb-10">Replied By (Admin)*</div>
                        <select name="replied_by">
                            <option value="">-- Select Admin --</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ old('replied_by', $contacts->replied_by) == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->full_name ?? $admin->email }}
                                </option>
                            @endforeach
                        </select>
                    </fieldset>
                    <fieldset class="created_at">
                        <div class="body-title mb-10">Created At</div>
                        <input type="text" class="form-control" value="{{ $contacts->created_at->format('d-m-Y H:i') }}" disabled>
                    </fieldset>
                </div>
                <div class="cols gap10">
                    <button class="tf-button w380" type="submit">Update Contact</button>
                    <a href="{{ route('admin.contacts.index') }}" class="tf-button style-3 w380">Cancel</a>
                </div>
            </form>
            <!-- /form-edit-contact -->
        </div>
    </div>
@endsection
