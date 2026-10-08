@extends('layouts.admin')
@section('title', 'Appointment Booking Details')
@section('actions')
    <a href="{{ route('admin.appointment-bookings.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection

@section('content')
<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">

    <div class="panel">
        <div class="panel-header"><h2>Appointment #{{ $booking->id }}</h2></div>
        <div class="panel-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
                <div>
                    <h4 style="color:var(--admin-gray);font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Customer Info</h4>
                    <p><strong>Name:</strong> {{ $booking->name }}</p>
                    <p><strong>Email:</strong> {{ $booking->email }}</p>
                    <p><strong>Phone:</strong> {{ $booking->phone }}</p>
                    @if($booking->otp_verified_at)
                        <p><strong>OTP Verified:</strong> <span class="badge badge-success">Yes</span></p>
                    @endif
                </div>
                <div>
                    <h4 style="color:var(--admin-gray);font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Pet Info</h4>
                    <p><strong>Pet Name:</strong> {{ $booking->pet_name }}</p>
                    <p><strong>Pet Type:</strong> {{ $booking->pet_type }}</p>
                </div>
                <div>
                    <h4 style="color:var(--admin-gray);font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Booking Info</h4>
                    <p><strong>Service:</strong> {{ $booking->service->title ?? '-' }}</p>
                    <p><strong>City:</strong> {{ $booking->city->name ?? '-' }}</p>
                    <p><strong>Preferred Date:</strong> {{ $booking->preferred_date->format('F d, Y') }}</p>
                </div>
                <div>
                    <h4 style="color:var(--admin-gray);font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Payment Info</h4>
                    @php
                        $payBadge = match($booking->payment_status) {
                            'paid'         => 'badge-success',
                            'pending'      => 'badge-warning',
                            'failed'       => 'badge-danger',
                            'not_required' => 'badge-secondary',
                            default        => 'badge-secondary'
                        };
                    @endphp
                    <p><strong>Payment Status:</strong> <span class="badge {{ $payBadge }}">{{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}</span></p>
                    @if($booking->amount)
                        <p><strong>Amount:</strong> ₹{{ number_format($booking->amount, 2) }}</p>
                    @endif
                    @if($booking->razorpay_payment_id)
                        <p><strong>Razorpay ID:</strong> <small>{{ $booking->razorpay_payment_id }}</small></p>
                    @endif
                    <p><strong>WhatsApp Sent:</strong>
                        @if($booking->whatsapp_sent)
                            <span class="badge badge-success">Yes</span>
                        @else
                            <span class="badge badge-secondary">No</span>
                        @endif
                    </p>
                </div>
            </div>

            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--admin-border);">
                <h4 style="color:var(--admin-gray);font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Timestamps</h4>
                <p><strong>Booked On:</strong> {{ $booking->created_at->format('M d, Y h:i A') }}</p>
            </div>

            @if($booking->message)
            <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--admin-border);">
                <h4 style="color:var(--admin-gray);font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">Message</h4>
                <p style="background:var(--admin-light);padding:14px;border-radius:8px;">{{ $booking->message }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="panel">
        <div class="panel-header"><h2>Update Status</h2></div>
        <div class="panel-body">
            <form action="{{ route('admin.appointment-bookings.update-status', $booking) }}" method="POST" class="admin-form">
                @csrf @method('PATCH')
                <div class="form-group">
                    <label>Current Status</label>
                    @php
                        $statusBadge = match($booking->status) {
                            'pending'   => 'badge-warning',
                            'confirmed' => 'badge-info',
                            'completed' => 'badge-success',
                            'cancelled' => 'badge-danger',
                            default     => 'badge-secondary'
                        };
                    @endphp
                    <div><span class="badge {{ $statusBadge }}" style="font-size:0.85rem;padding:6px 16px;">{{ ucfirst($booking->status) }}</span></div>
                </div>
                <div class="form-group">
                    <label>Change Status</label>
                    <select name="status" class="form-control">
                        <option value="pending"   {{ $booking->status == 'pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn-admin btn-admin-primary" style="width:100%;justify-content:center;">
                    <i class="fas fa-check"></i> Update Status
                </button>
                @if(session('success'))
                    <div class="alert-admin alert-admin-success" style="margin-top:12px;">{{ session('success') }}</div>
                @endif
            </form>
        </div>
    </div>

</div>
@endsection
