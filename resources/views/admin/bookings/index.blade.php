@extends('layouts.admin')
@section('title', 'Vaccination Bookings')

@section('content')
<div class="panel">
    <div class="panel-header">
        <h2>All Bookings</h2>
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="filter-bar" style="margin:0;">
            <input type="text" name="search" placeholder="Search name/email/phone..." value="{{ request('search') }}">
            <select name="status" onchange="this.form.submit()">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn-admin btn-admin-outline btn-admin-sm"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <div class="table-responsive"><table class="data-table">
        <thead><tr><th>Customer</th><th>Pet</th><th>Vaccination</th><th>City</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr>
                <td>
                    <strong>{{ $booking->name }}</strong><br>
                    <small style="color:var(--admin-gray)">{{ $booking->phone }} &bull; {{ $booking->email }}</small>
                </td>
                <td>{{ $booking->pet_name }}<br><small style="color:var(--admin-gray)">{{ $booking->pet_type }}</small></td>
                <td>{{ $booking->vaccinationType->name ?? '-' }}</td>
                <td>{{ $booking->city->name ?? '-' }}</td>
                <td>{{ $booking->preferred_date->format('M d, Y') }}</td>
                <td>
                    @php
                        $statusBadge = match($booking->status) {
                            'pending' => 'badge-warning',
                            'confirmed' => 'badge-info',
                            'completed' => 'badge-success',
                            'cancelled' => 'badge-danger',
                            default => 'badge-secondary'
                        };
                    @endphp
                    <span class="badge {{ $statusBadge }}">{{ ucfirst($booking->status) }}</span>
                </td>
                <td>
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="btn-admin btn-admin-info btn-admin-sm"><i class="fas fa-eye"></i></a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:30px;color:var(--admin-gray);">No bookings found</td></tr>
            @endforelse
        </tbody>
    </table></div>
    @if($bookings->hasPages())
    <div style="padding:16px 24px;">{{ $bookings->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection
