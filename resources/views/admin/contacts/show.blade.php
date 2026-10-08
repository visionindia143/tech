@extends('layouts.admin')
@section('title', 'Message from ' . $contact->name)

@section('actions')
    <a href="{{ route('admin.contacts.index') }}" class="btn-admin btn-admin-outline btn-admin-sm">
        <i class="fas fa-arrow-left"></i> Back to Messages
    </a>
@endsection

@section('content')
<div class="panel" style="max-width:760px;">
    <div class="panel-header">
        <div>
            <h2>{{ $contact->subject ?: 'Message from ' . $contact->name }}</h2>
            <small style="color:var(--admin-gray);">Received {{ $contact->created_at->format('F d, Y \a\t h:i A') }}</small>
        </div>
        <span class="badge {{ $contact->is_read ? 'badge-secondary' : 'badge-warning' }}">
            {{ $contact->is_read ? 'Read' : 'Unread' }}
        </span>
    </div>

    <div class="panel-body">
        {{-- Sender Info --}}
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:24px;padding:16px;background:var(--admin-light);border-radius:8px;">
            <div>
                <div style="font-size:0.73rem;text-transform:uppercase;letter-spacing:0.5px;color:var(--admin-gray);font-weight:600;margin-bottom:4px;">Name</div>
                <div style="font-weight:600;">{{ $contact->name }}</div>
            </div>
            <div>
                <div style="font-size:0.73rem;text-transform:uppercase;letter-spacing:0.5px;color:var(--admin-gray);font-weight:600;margin-bottom:4px;">Email</div>
                <a href="mailto:{{ $contact->email }}" style="color:var(--admin-primary);font-weight:500;">{{ $contact->email }}</a>
            </div>
            @if($contact->phone)
            <div>
                <div style="font-size:0.73rem;text-transform:uppercase;letter-spacing:0.5px;color:var(--admin-gray);font-weight:600;margin-bottom:4px;">Phone</div>
                <a href="tel:{{ $contact->phone }}" style="font-weight:500;">{{ $contact->phone }}</a>
            </div>
            @endif
        </div>

        {{-- Message Body --}}
        <div style="font-size:0.88rem;font-weight:600;color:var(--admin-gray);margin-bottom:8px;text-transform:uppercase;letter-spacing:0.5px;">Message</div>
        <div style="background:white;border:1px solid var(--admin-border);border-radius:8px;padding:20px;line-height:1.8;white-space:pre-wrap;font-size:0.92rem;">{{ $contact->message }}</div>

        {{-- Actions --}}
        <div style="display:flex;gap:10px;margin-top:20px;flex-wrap:wrap;">
            <a href="mailto:{{ $contact->email }}" class="btn-admin btn-admin-primary">
                <i class="fas fa-reply"></i> Reply via Email
            </a>
            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
                  onsubmit="return confirm('Delete this message permanently?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-admin btn-admin-danger">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
