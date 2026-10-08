@extends('layouts.frontend')

@section('meta_title', 'Appointment Confirmed #' . $booking->id . ' - ' . $settings['site_name'])
@section('meta_description', 'Your appointment booking confirmation for ' . $booking->pet_name . '.')

@section('styles')
<style>
.appt-confirm-page {
    padding: 60px 0 80px;
    background: #F8F9FC;
    min-height: 70vh;
}

/* ── Confirmation card ── */
.appt-confirm-wrap {
    max-width: 760px;
    margin: 0 auto;
}

.appt-confirm-banner {
    border-radius: 20px 20px 0 0;
    padding: 36px 40px;
    display: flex;
    align-items: center;
    gap: 24px;
}
.appt-confirm-banner.pending   { background: linear-gradient(135deg, #FFFBEB, #FEF3C7); border: 1px solid #FDE68A; border-bottom: none; }
.appt-confirm-banner.confirmed { background: linear-gradient(135deg, #ECFDF5, #D1FAE5); border: 1px solid #A7F3D0; border-bottom: none; }
.appt-confirm-banner.completed { background: linear-gradient(135deg, #EFF6FF, #DBEAFE); border: 1px solid #BFDBFE; border-bottom: none; }
.appt-confirm-banner.cancelled { background: linear-gradient(135deg, #FEF2F2, #FEE2E2); border: 1px solid #FECACA; border-bottom: none; }

.appt-confirm-icon {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    flex-shrink: 0;
}
.pending   .appt-confirm-icon { background: #FEF3C7; color: #D97706; box-shadow: 0 0 0 8px rgba(217,119,6,0.1); }
.confirmed .appt-confirm-icon { background: #D1FAE5; color: #059669; box-shadow: 0 0 0 8px rgba(5,150,105,0.1); }
.completed .appt-confirm-icon { background: #DBEAFE; color: #2563EB; box-shadow: 0 0 0 8px rgba(37,99,235,0.1); }
.cancelled .appt-confirm-icon { background: #FEE2E2; color: #DC2626; box-shadow: 0 0 0 8px rgba(220,38,38,0.1); }

.appt-confirm-banner-text h2 {
    font-family: var(--font-display);
    font-size: 1.5rem;
    margin-bottom: 6px;
}
.pending   .appt-confirm-banner-text h2 { color: #92400E; }
.confirmed .appt-confirm-banner-text h2 { color: #065F46; }
.completed .appt-confirm-banner-text h2 { color: #1E40AF; }
.cancelled .appt-confirm-banner-text h2 { color: #991B1B; }
.appt-confirm-banner-text p {
    font-size: 0.9rem;
    opacity: 0.8;
    margin: 0;
    line-height: 1.5;
}
.appt-confirm-banner-text .booking-ref {
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    margin-top: 6px;
    opacity: 0.65;
}

/* ── Detail card ── */
.appt-detail-card {
    background: #fff;
    border: 1px solid var(--clr-border);
    border-top: none;
    border-radius: 0 0 20px 20px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.07);
    overflow: hidden;
}

.appt-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
}

.appt-detail-section {
    padding: 28px 32px;
    border-bottom: 1px solid var(--clr-border);
}
.appt-detail-section:nth-child(odd) {
    border-right: 1px solid var(--clr-border);
}
.appt-detail-section:last-child,
.appt-detail-section:nth-last-child(2):nth-child(odd) {
    border-bottom: none;
}

.appt-detail-section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.09em;
    text-transform: uppercase;
    color: var(--clr-gray);
    margin-bottom: 16px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--clr-border);
}
.appt-detail-section-title i {
    width: 26px;
    height: 26px;
    border-radius: 6px;
    background: var(--clr-warm);
    color: var(--clr-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    flex-shrink: 0;
}

.appt-detail-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 10px;
    font-size: 0.88rem;
}
.appt-detail-row:last-child { margin-bottom: 0; }
.appt-detail-label { color: var(--clr-gray); font-weight: 500; white-space: nowrap; }
.appt-detail-value { color: var(--clr-secondary); font-weight: 600; text-align: right; }

/* ── Status badge ── */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 99px;
    font-size: 0.8rem;
    font-weight: 700;
}
.status-badge.pending   { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }
.status-badge.confirmed { background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; }
.status-badge.completed { background: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE; }
.status-badge.cancelled { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }

.pay-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 99px;
    font-size: 0.8rem;
    font-weight: 700;
}
.pay-badge.paid         { background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; }
.pay-badge.pending      { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }
.pay-badge.failed       { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
.pay-badge.not_required { background: #F5F3FF; color: #7C3AED; border: 1px solid #DDD6FE; }

/* ── Actions bar ── */
.appt-actions {
    padding: 24px 32px;
    background: #FAFAFA;
    border-top: 1px solid var(--clr-border);
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
}
.appt-actions-note {
    font-size: 0.82rem;
    color: var(--clr-gray);
    display: flex;
    align-items: center;
    gap: 6px;
}
.appt-actions-btns { display: flex; gap: 10px; flex-wrap: wrap; }

/* ── Timeline / next steps ── */
.appt-timeline-card {
    background: #fff;
    border: 1px solid var(--clr-border);
    border-radius: 16px;
    padding: 28px 32px;
    margin-top: 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
}
.appt-timeline-card h3 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--clr-secondary);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.appt-timeline {
    display: flex;
    flex-direction: column;
    gap: 0;
    position: relative;
}
.appt-timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 20px;
    bottom: 20px;
    width: 2px;
    background: var(--clr-border);
}
.appt-tl-item {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    padding-bottom: 20px;
    position: relative;
}
.appt-tl-item:last-child { padding-bottom: 0; }
.appt-tl-dot {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}
.appt-tl-dot.done   { background: #ECFDF5; color: #059669; border: 2px solid #A7F3D0; }
.appt-tl-dot.active { background: var(--clr-warm); color: var(--clr-primary); border: 2px solid rgba(255,107,53,0.3); }
.appt-tl-dot.next   { background: #F9FAFB; color: var(--clr-gray); border: 2px solid var(--clr-border); }
.appt-tl-text h4    { font-size: 0.9rem; font-weight: 600; color: var(--clr-secondary); margin-bottom: 2px; }
.appt-tl-text p     { font-size: 0.82rem; color: var(--clr-gray); margin: 0; line-height: 1.5; }

/* ── Responsive ── */
@media (max-width: 640px) {
    .appt-confirm-banner { padding: 24px 20px; flex-direction: column; text-align: center; border-radius: 16px 16px 0 0; }
    .appt-detail-grid    { grid-template-columns: 1fr; }
    .appt-detail-section:nth-child(odd) { border-right: none; }
    .appt-detail-section:nth-last-child(2):nth-child(odd) { border-bottom: 1px solid var(--clr-border); }
    .appt-detail-section { padding: 20px; }
    .appt-actions { flex-direction: column; align-items: flex-start; }
    .appt-timeline-card { padding: 20px; }
}
</style>
@endsection

@section('content')

<div class="page-hero" style="padding:40px 0 48px;">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> /
            <a href="{{ route('appointment') }}">Appointment Booking</a> /
            Booking #{{ $booking->id }}
        </div>
    </div>
</div>

<div class="appt-confirm-page">
    <div class="container">
        <div class="appt-confirm-wrap fade-up">

            @php
                $statusClass = $booking->status;
                $statusIcons = [
                    'pending'   => 'fa-clock',
                    'confirmed' => 'fa-check',
                    'completed' => 'fa-flag-checkered',
                    'cancelled' => 'fa-times',
                ];
                $statusHeadings = [
                    'pending'   => 'Booking Received!',
                    'confirmed' => 'Appointment Confirmed!',
                    'completed' => 'Appointment Completed',
                    'cancelled' => 'Booking Cancelled',
                ];
                $statusMessages = [
                    'pending'   => 'Your appointment request has been submitted. Our team will review and confirm your slot within 24 hours.',
                    'confirmed' => 'Great news! Your appointment has been confirmed. Please arrive on time on your preferred date.',
                    'completed' => 'Your appointment has been completed. Thank you for choosing us!',
                    'cancelled' => 'This appointment has been cancelled. Please book a new appointment if needed.',
                ];
            @endphp

            {{-- ── STATUS BANNER ── --}}
            <div class="appt-confirm-banner {{ $statusClass }}">
                <div class="appt-confirm-icon">
                    <i class="fas {{ $statusIcons[$booking->status] ?? 'fa-calendar' }}"></i>
                </div>
                <div class="appt-confirm-banner-text">
                    <h2>{{ $statusHeadings[$booking->status] ?? 'Booking Details' }}</h2>
                    <p>{{ $statusMessages[$booking->status] ?? '' }}</p>
                    <div class="booking-ref">BOOKING REF: #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                </div>
            </div>

            {{-- ── DETAIL CARD ── --}}
            <div class="appt-detail-card">
                <div class="appt-detail-grid">

                    {{-- Customer Info --}}
                    <div class="appt-detail-section">
                        <div class="appt-detail-section-title">
                            <i class="fas fa-user"></i> Customer Details
                        </div>
                        <div class="appt-detail-row">
                            <span class="appt-detail-label">Name</span>
                            <span class="appt-detail-value">{{ $booking->name }}</span>
                        </div>
                        <div class="appt-detail-row">
                            <span class="appt-detail-label">Phone</span>
                            <span class="appt-detail-value">{{ $booking->phone }}</span>
                        </div>
                        <div class="appt-detail-row">
                            <span class="appt-detail-label">Email</span>
                            <span class="appt-detail-value" style="word-break:break-all;">{{ $booking->email }}</span>
                        </div>
                    </div>

                    {{-- Pet Info --}}
                    <div class="appt-detail-section">
                        <div class="appt-detail-section-title">
                            <i class="fas fa-paw"></i> Pet Details
                        </div>
                        <div class="appt-detail-row">
                            <span class="appt-detail-label">Pet Name</span>
                            <span class="appt-detail-value">{{ $booking->pet_name }}</span>
                        </div>
                        <div class="appt-detail-row">
                            <span class="appt-detail-label">Pet Type</span>
                            <span class="appt-detail-value">{{ $booking->pet_type }}</span>
                        </div>
                    </div>

                    {{-- Appointment Info --}}
                    <div class="appt-detail-section">
                        <div class="appt-detail-section-title">
                            <i class="fas fa-calendar-alt"></i> Appointment Details
                        </div>
                        <div class="appt-detail-row">
                            <span class="appt-detail-label">Service</span>
                            <span class="appt-detail-value">{{ $booking->service->title ?? '—' }}</span>
                        </div>
                        <div class="appt-detail-row">
                            <span class="appt-detail-label">Preferred Date</span>
                            <span class="appt-detail-value">{{ $booking->preferred_date->format('D, M d, Y') }}</span>
                        </div>
                        <div class="appt-detail-row">
                            <span class="appt-detail-label">City</span>
                            <span class="appt-detail-value">{{ $booking->city->name ?? '—' }}</span>
                        </div>
                        <div class="appt-detail-row">
                            <span class="appt-detail-label">Booked On</span>
                            <span class="appt-detail-value">{{ $booking->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    {{-- Status & Payment Info --}}
                    <div class="appt-detail-section">
                        <div class="appt-detail-section-title">
                            <i class="fas fa-info-circle"></i> Status & Payment
                        </div>
                        <div class="appt-detail-row">
                            <span class="appt-detail-label">Booking Status</span>
                            <span class="appt-detail-value">
                                <span class="status-badge {{ $booking->status }}">
                                    <i class="fas {{ $statusIcons[$booking->status] ?? 'fa-circle' }}"></i>
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </span>
                        </div>
                        <div class="appt-detail-row">
                            <span class="appt-detail-label">Payment</span>
                            <span class="appt-detail-value">
                                <span class="pay-badge {{ $booking->payment_status }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}
                                </span>
                            </span>
                        </div>
                        @if($booking->amount)
                        <div class="appt-detail-row">
                            <span class="appt-detail-label">Amount Paid</span>
                            <span class="appt-detail-value">₹{{ number_format($booking->amount, 2) }}</span>
                        </div>
                        @endif
                        @if($booking->otp_verified_at)
                        <div class="appt-detail-row">
                            <span class="appt-detail-label">Phone Verified</span>
                            <span class="appt-detail-value" style="color:#059669;"><i class="fas fa-check-circle"></i> Yes</span>
                        </div>
                        @endif
                    </div>

                </div>

                {{-- Message --}}
                @if($booking->message)
                <div style="padding:24px 32px;border-top:1px solid var(--clr-border);">
                    <div class="appt-detail-section-title" style="margin-bottom:10px;">
                        <i class="fas fa-comment-alt"></i> Your Notes
                    </div>
                    <p style="font-size:0.9rem;color:var(--clr-gray);background:#FAFAFA;padding:14px 18px;border-radius:10px;border:1px solid var(--clr-border);margin:0;line-height:1.6;">
                        {{ $booking->message }}
                    </p>
                </div>
                @endif

                {{-- Actions bar --}}
                <div class="appt-actions">
                    <div class="appt-actions-note">
                        <i class="fas fa-bell" style="color:var(--clr-primary);"></i>
                        @if($booking->status === 'pending')
                            We'll confirm your slot and reach out within 24 hours.
                        @elseif($booking->status === 'confirmed')
                            Please arrive on time. Bring this booking reference with you.
                        @else
                            Thank you for choosing {{ $settings['site_name'] }}.
                        @endif
                    </div>
                    <div class="appt-actions-btns">
                        <a href="{{ route('home') }}" class="btn btn-outline" style="padding:10px 20px;font-size:0.9rem;">
                            <i class="fas fa-home"></i> Home
                        </a>
                        <a href="{{ route('appointment') }}" class="btn btn-primary" style="padding:10px 20px;font-size:0.9rem;">
                            <i class="fas fa-plus"></i> Book Another
                        </a>
                        @if($settings['whatsapp_number'])
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings['whatsapp_number']) }}?text={{ urlencode('Hi, I need help with my appointment booking #' . $booking->id) }}"
                           target="_blank" rel="noopener"
                           style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:#25D366;color:white;border-radius:10px;font-weight:600;font-size:0.9rem;text-decoration:none;">
                            <i class="fab fa-whatsapp"></i> WhatsApp Us
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── NEXT STEPS TIMELINE ── --}}
            @if($booking->status !== 'cancelled')
            <div class="appt-timeline-card">
                <h3><i class="fas fa-list-check" style="color:var(--clr-primary);"></i> What Happens Next?</h3>
                <div class="appt-timeline">
                    <div class="appt-tl-item">
                        <div class="appt-tl-dot done"><i class="fas fa-check"></i></div>
                        <div class="appt-tl-text">
                            <h4>Booking Submitted</h4>
                            <p>Your appointment request has been received on {{ $booking->created_at->format('M d, Y \a\t h:i A') }}.</p>
                        </div>
                    </div>
                    <div class="appt-tl-item">
                        <div class="appt-tl-dot {{ $booking->status !== 'pending' ? 'done' : 'active' }}">
                            <i class="fas {{ $booking->status !== 'pending' ? 'fa-check' : 'fa-clock' }}"></i>
                        </div>
                        <div class="appt-tl-text">
                            <h4>Confirmation Call / Message</h4>
                            <p>Our team will contact you via phone or WhatsApp to confirm the exact time slot.</p>
                        </div>
                    </div>
                    <div class="appt-tl-item">
                        <div class="appt-tl-dot {{ $booking->status === 'completed' ? 'done' : 'next' }}">
                            <i class="fas {{ $booking->status === 'completed' ? 'fa-check' : 'fa-calendar-day' }}"></i>
                        </div>
                        <div class="appt-tl-text">
                            <h4>Your Appointment Day</h4>
                            <p>Visit us on {{ $booking->preferred_date->format('D, F d, Y') }} for your scheduled service.</p>
                        </div>
                    </div>
                    <div class="appt-tl-item">
                        <div class="appt-tl-dot {{ $booking->status === 'completed' ? 'done' : 'next' }}">
                            <i class="fas {{ $booking->status === 'completed' ? 'fa-check' : 'fa-flag-checkered' }}"></i>
                        </div>
                        <div class="appt-tl-text">
                            <h4>Service Completed</h4>
                            <p>After the appointment, you'll receive a summary and care follow-up if needed.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
