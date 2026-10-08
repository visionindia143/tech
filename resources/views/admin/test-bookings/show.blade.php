@extends('layouts.admin')
@section('title', 'Diagnostic Booking Details')
@section('actions')
    <a href="{{ route('admin.test-bookings.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
    <div class="panel">
        <div class="panel-header"><h2>Booking #{{ $booking->id }}</h2></div>
        <div class="panel-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                <div>
                    <h4 style="color:var(--admin-gray);font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Customer Info</h4>
                    <p><strong>Name:</strong> {{ $booking->name }}</p>
                    <p><strong>Email:</strong> {{ $booking->email }}</p>
                    <p><strong>Phone:</strong> {{ $booking->phone }}</p>
                    <p><strong>Pet Type:</strong> {{ $booking->pet_type ?: '-' }}</p>
                    <p><strong>Pet Name:</strong> {{ $booking->pet_name ?: '-' }}</p>
                    <p><strong>Age:</strong> {{ $booking->age ?: '-' }}</p>
                    <p><strong>Gender:</strong> {{ $booking->gender ? ucfirst($booking->gender) : '-' }}</p>
                </div>
                <div>
                    <h4 style="color:var(--admin-gray);font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Test Info</h4>
                    <p><strong>Test:</strong> {{ $booking->testType->name ?? '-' }}</p>
                    <p><strong>Category:</strong> {{ $booking->testType->category ?? '-' }}</p>
                    <p><strong>City:</strong> {{ $booking->city->name ?? '-' }}</p>
                    <p><strong>Preferred Date:</strong> {{ $booking->preferred_date->format('F d, Y') }}</p>
                    <p><strong>Preferred Time:</strong> {{ $booking->preferred_time ?: '-' }}</p>
                </div>
                <div>
                    <h4 style="color:var(--admin-gray);font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Medical Notes</h4>
                    <p><strong>Symptoms:</strong> {{ $booking->symptoms ?: '-' }}</p>
                    <p><strong>History:</strong> {{ $booking->medical_history ?: '-' }}</p>
                </div>
                <div>
                    <h4 style="color:var(--admin-gray);font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Timestamps</h4>
                    <p><strong>Booked On:</strong> {{ $booking->created_at->format('M d, Y h:i A') }}</p>
                    <p><strong>Updated:</strong> {{ $booking->updated_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>

            @if($booking->address)
                <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--admin-border);">
                    <h4 style="color:var(--admin-gray);font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Address</h4>
                    <p style="background:var(--admin-light);padding:14px;border-radius:8px;">{{ $booking->address }}</p>
                </div>
            @endif

            @if($booking->message)
                <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--admin-border);">
                    <h4 style="color:var(--admin-gray);font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Message</h4>
                    <p style="background:var(--admin-light);padding:14px;border-radius:8px;">{{ $booking->message }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="panel">
        <div class="panel-header"><h2>Update Status</h2></div>
        <div class="panel-body">
            <form action="{{ route('admin.test-bookings.update-status', $booking) }}" method="POST" class="admin-form">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <label>Current Status</label>
                    @php
                        $statusBadge = match($booking->status) {
                            'pending' => 'badge-warning',
                            'confirmed' => 'badge-info',
                            'completed' => 'badge-success',
                            'cancelled' => 'badge-danger',
                            default => 'badge-secondary'
                        };
                    @endphp
                    <div><span class="badge {{ $statusBadge }}" style="font-size:0.85rem;padding:6px 16px;">{{ ucfirst($booking->status) }}</span></div>
                </div>
                <div class="form-group">
                    <label>Change Status</label>
                    <select name="status" class="form-control">
                        <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn-admin btn-admin-primary" style="width:100%;justify-content:center;">
                    <i class="fas fa-check"></i> Update Status
                </button>
            </form>
        </div>
    </div>
</div>
@endsection