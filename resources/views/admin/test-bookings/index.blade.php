@extends('layouts.admin')
@section('title', 'Diagnostic Bookings')

@section('content')
<div class="panel">
    <div class="panel-header">
        <h2>All Diagnostic Bookings</h2>
        <form action="{{ route('admin.test-bookings.index') }}" method="GET" class="filter-bar" style="margin:0;">
            <input type="text" name="search" placeholder="Search name/email/phone/pet/test..." value="{{ request('search') }}">
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
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Pet Details</th>
                    <th>Test</th>
                    {{-- <th>Medical Info</th> --}}
                    <th>City</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>
                            <strong>{{ $booking->name }}</strong><br>
                            <small style="color:var(--admin-gray)">{{ $booking->phone }} &bull; {{ $booking->email }}</small><br>
                            <small style="color:var(--admin-gray)">{{ $booking->age ? $booking->age . ' yrs' : 'Age not shared' }}@if($booking->gender) &bull; {{ ucfirst($booking->gender) }}@endif</small>
                        </td>
                        <td>
                            <strong>{{ $booking->pet_name ?? '-' }}</strong><br>
                            <small style="color:var(--admin-gray)">{{ $booking->pet_type ?? '-' }}</small>
                        </td>
                        <td>
                            {{ $booking->testType->name ?? '-' }}<br>
                            <small style="color:var(--admin-gray)">{{ $booking->testType->category ?? '-' }}</small>
                        </td>
                        {{-- <td>
                            @if($booking->symptoms)
                                <small><strong>Symptoms:</strong> {{ Str::limit($booking->symptoms, 70) }}</small><br>
                            @endif
                            @if($booking->medical_history)
                                <small><strong>History:</strong> {{ Str::limit($booking->medical_history, 70) }}</small>
                            @endif
                        </td> --}}
                        <td>{{ $booking->city->name ?? '-' }}</td>
                        <td>
                            {{ $booking->preferred_date->format('M d, Y') }}
                            @if($booking->preferred_time)
                                <br><small style="color:var(--admin-gray)">{{ $booking->preferred_time }}</small>
                            @endif
                        </td>
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
                            <a href="{{ route('admin.test-bookings.show', $booking) }}" class="btn-admin btn-admin-info btn-admin-sm"><i class="fas fa-eye"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" style="text-align:center;padding:30px;color:var(--admin-gray);">No diagnostic bookings found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
        <div style="padding:16px 24px;">{{ $bookings->appends(request()->query())->links() }}</div>
    @endif
</div>
@endsection