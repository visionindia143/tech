@extends('layouts.admin')
@section('title', 'Booking #' . $booking->id)
@section('actions')
    <a href="{{ route('admin.adoption-bookings.index') }}" class="btn-admin btn-admin-outline"><i class="fas fa-arrow-left"></i> Back</a>
@endsection
@section('content')
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

    {{-- Booking Details --}}
    <div class="panel">
        <div class="panel-header"><h2><i class="fas fa-user" style="margin-right:8px;color:var(--admin-primary);"></i> Customer Details</h2></div>
        <div class="panel-body">
            <table style="width:100%;border-collapse:collapse;">
                <tr><td style="padding:8px 0;color:var(--admin-gray);width:40%;">Name</td><td style="font-weight:600;">{{ $booking->name }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--admin-gray);">Phone</td><td>{{ $booking->phone }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--admin-gray);">Email</td><td>{{ $booking->email }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--admin-gray);">City</td><td>{{ $booking->city ?: '—' }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--admin-gray);">Address</td><td>{{ $booking->address ?: '—' }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--admin-gray);">Notes</td><td>{{ $booking->notes ?: '—' }}</td></tr>
                <tr><td style="padding:8px 0;color:var(--admin-gray);">OTP Verified</td>
                    <td>
                        @if($booking->otp_verified_at)
                            <span class="badge badge-success"><i class="fas fa-check"></i> {{ $booking->otp_verified_at->format('d M Y H:i') }}</span>
                        @else
                            <span class="badge badge-danger">Not Verified</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Pet + Payment + Status --}}
    <div>
        <div class="panel">
            <div class="panel-header"><h2><i class="fas fa-paw" style="margin-right:8px;color:var(--admin-primary);"></i> Pet & Booking Info</h2></div>
            <div class="panel-body">
                @if($booking->pet)
                <div style="display:flex;gap:16px;align-items:center;padding:12px;background:var(--admin-light);border-radius:8px;margin-bottom:16px;">
                    <img src="{{ $booking->pet->first_image_url }}" style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
                    <div>
                        <div style="font-weight:700;font-size:1rem;">{{ $booking->pet->name }}</div>
                        <div style="color:var(--admin-gray);font-size:0.85rem;">{{ $booking->pet->breed }} &bull; {{ ucfirst($booking->pet->pet_type) }}</div>
                    </div>
                </div>
                @endif
                <table style="width:100%;border-collapse:collapse;">
                    <tr><td style="padding:8px 0;color:var(--admin-gray);width:45%;">Booking Date</td><td>{{ $booking->created_at->format('d M Y H:i') }}</td></tr>
                    <tr><td style="padding:8px 0;color:var(--admin-gray);">Amount</td><td><strong>{{ $booking->amount ? '₹'.number_format($booking->amount) : 'Free' }}</strong></td></tr>
                    <tr><td style="padding:8px 0;color:var(--admin-gray);">Payment</td><td>{!! $booking->payment_badge !!}</td></tr>
                    @if($booking->razorpay_payment_id)
                    <tr><td style="padding:8px 0;color:var(--admin-gray);">Payment ID</td><td style="font-size:0.8rem;word-break:break-all;">{{ $booking->razorpay_payment_id }}</td></tr>
                    @endif
                    <tr><td style="padding:8px 0;color:var(--admin-gray);">WhatsApp</td>
                        <td>
                            @if($booking->whatsapp_sent)
                                <span class="badge badge-success"><i class="fab fa-whatsapp"></i> Sent</span>
                            @else
                                <span class="badge badge-secondary">Not Sent</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Update Status --}}
        <div class="panel" style="margin-top:16px;">
            <div class="panel-header"><h2><i class="fas fa-edit" style="margin-right:8px;color:var(--admin-primary);"></i> Update Status</h2></div>
            <div class="panel-body">
                <p style="font-size:0.85rem;color:var(--admin-gray);margin-bottom:16px;">
                    Current: {!! $booking->status_badge !!}
                    &nbsp;&nbsp; Changing status will send a WhatsApp notification to the customer.
                </p>
                <form action="{{ route('admin.adoption-bookings.update-status', $booking) }}" method="POST" style="display:flex;gap:12px;align-items:center;">
                    @csrf @method('PATCH')
                    <select name="status" class="form-control" style="flex:1;padding:10px;">
                        @foreach(['pending','confirmed','completed','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $booking->status==$s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-admin btn-admin-primary" style="white-space:nowrap;">
                        <i class="fas fa-save"></i> Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
