@extends('layouts.admin')
@section('title', 'Contact Messages')

@section('actions')
    @if($unreadCount > 0)
        <form action="{{ route('admin.contacts.mark-all-read') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn-admin btn-admin-outline btn-admin-sm">
                <i class="fas fa-check-double"></i> Mark All Read
            </button>
        </form>
    @endif
@endsection

@section('content')

<div class="filter-bar">
    <form method="GET" action="{{ route('admin.contacts.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;width:100%;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, subject..." style="flex:1;min-width:200px;">
        <select name="status">
            <option value="">All Messages</option>
            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread Only</option>
            <option value="read"   {{ request('status') === 'read'   ? 'selected' : '' }}>Read Only</option>
        </select>
        <button type="submit" class="btn-admin btn-admin-primary btn-admin-sm">
            <i class="fas fa-search"></i> Filter
        </button>
        @if(request()->hasAny(['search','status']))
            <a href="{{ route('admin.contacts.index') }}" class="btn-admin btn-admin-outline btn-admin-sm">Clear</a>
        @endif
    </form>
</div>

<div class="panel">
    <div class="panel-header">
        <h2>
            Contact Messages
            @if($unreadCount > 0)
                <span class="badge badge-danger" style="margin-left:8px;">{{ $unreadCount }} Unread</span>
            @endif
        </h2>
        <span style="font-size:0.82rem;color:var(--admin-gray);">{{ $contacts->total() }} total</span>
    </div>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Message Preview</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr style="{{ !$contact->is_read ? 'background:#FFFBEB;' : '' }}">
                    <td>
                        <strong>{{ $contact->name }}</strong><br>
                        <small style="color:var(--admin-gray)">{{ $contact->email }}</small>
                        @if($contact->phone)
                            <br><small style="color:var(--admin-gray)">{{ $contact->phone }}</small>
                        @endif
                    </td>
                    <td>{{ $contact->subject ?: '-' }}</td>
                    <td style="max-width:220px;">
                        <span style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-size:0.84rem;color:var(--admin-gray);">
                            {{ $contact->message }}
                        </span>
                    </td>
                    <td style="white-space:nowrap;font-size:0.83rem;">{{ $contact->created_at->format('M d, Y') }}<br><span style="color:var(--admin-gray);">{{ $contact->created_at->format('h:i A') }}</span></td>
                    <td>
                        @if($contact->is_read)
                            <span class="badge badge-secondary">Read</span>
                        @else
                            <span class="badge badge-warning">Unread</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="btn-admin btn-admin-info btn-admin-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
                                  onsubmit="return confirm('Delete this message?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-admin btn-admin-danger btn-admin-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:40px;color:var(--admin-gray);">
                        <i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:10px;opacity:0.3;"></i>
                        No messages found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
@if($contacts->hasPages())
<div class="admin-pagination">
    {{ $contacts->links('pagination::simple-bootstrap-4') }}
</div>
@endif

@endsection
