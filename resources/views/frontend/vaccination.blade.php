@extends('layouts.frontend')

@section('meta_title', 'Book Pet Vaccination - ' . $settings['site_name'])
@section('meta_description', 'Book a vaccination appointment for your pet. We offer all types of pet vaccinations across multiple cities.')

@section('styles')
<style>
/* ═══════════════════════════════════════════
   VACCINATION PAGE STYLES
═══════════════════════════════════════════ */

/* ── Hero ── */
.vac-hero {
    background: linear-gradient(135deg, var(--clr-secondary) 0%, #2D1B69 60%, var(--clr-dark) 100%);
    padding: 72px 0 80px;
    position: relative;
    overflow: hidden;
}
.vac-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 60% 80% at 80% 50%, rgba(255,107,53,0.18) 0%, transparent 70%);
    pointer-events: none;
}
.vac-hero-inner {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: 40px;
}
.vac-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,107,53,0.15);
    border: 1px solid rgba(255,107,53,0.3);
    color: var(--clr-primary);
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 6px 14px;
    border-radius: 999px;
    margin-bottom: 18px;
}
.vac-hero h1 {
    font-family: var(--font-display);
    font-size: clamp(2rem, 4vw, 2.8rem);
    color: #fff;
    margin-bottom: 14px;
    line-height: 1.15;
}
.vac-hero-sub {
    color: rgba(255,255,255,0.65);
    font-size: 1rem;
    max-width: 480px;
    line-height: 1.65;
    margin-bottom: 28px;
}
.vac-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    color: rgba(255,255,255,0.5);
}
.vac-breadcrumb a { color: rgba(255,255,255,0.7); text-decoration: none; transition: color 0.2s; }
.vac-breadcrumb a:hover { color: var(--clr-primary); }
.vac-breadcrumb i { font-size: 0.65rem; }
.vac-hero-visual {
    width: 160px;
    height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    border: 2px solid rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 5rem;
    flex-shrink: 0;
}

/* ── Trust bar ── */
.vac-trust-bar {
    background: white;
    border-bottom: 1px solid var(--clr-border);
    padding: 18px 0;
}
.vac-trust-list {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 40px;
    flex-wrap: wrap;
}
.vac-trust-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--clr-secondary);
}
.vac-trust-item i {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
}
.vac-trust-item.orange i { background: var(--clr-warm); color: var(--clr-primary); }
.vac-trust-item.green  i { background: #ECFDF5; color: #059669; }
.vac-trust-item.blue   i { background: #EFF6FF; color: #3B82F6; }
.vac-trust-item.purple i { background: #F5F3FF; color: #7C3AED; }

/* ── Page layout ── */
.vac-page {
    padding: 60px 0 80px;
    background: #F8F9FC;
}
.vac-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 32px;
    align-items: start;
}

/* ── Form card ── */
.vac-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    overflow: hidden;
}
.vac-card-header {
    background: linear-gradient(135deg, var(--clr-primary), #FF8C5A);
    padding: 28px 32px;
    color: white;
}
.vac-card-header h2 {
    font-family: var(--font-display);
    font-size: 1.5rem;
    margin-bottom: 6px;
    color: #fff;
}
.vac-card-header p {
    opacity: 0.85;
    font-size: 0.9rem;
}
.vac-card-body { padding: 32px; }

/* ── Step indicator ── */
.vac-steps {
    display: flex;
    align-items: center;
    margin-bottom: 32px;
    gap: 0;
}
.vac-step {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 1;
}
.vac-step-num {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--clr-warm);
    color: var(--clr-primary);
    font-size: 0.85rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.3s;
}
.vac-step.active .vac-step-num {
    background: var(--clr-primary);
    color: white;
    box-shadow: 0 0 0 4px rgba(255,107,53,0.2);
}
.vac-step-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--clr-gray);
}
.vac-step.active .vac-step-label { color: var(--clr-primary); }
.vac-step-line {
    flex: 1;
    height: 2px;
    background: var(--clr-border);
    margin: 0 8px;
}

/* ── Notices ── */
.vac-notice {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 24px;
    font-size: 0.88rem;
    line-height: 1.5;
}
.vac-notice.blue  { background: #EFF6FF; border: 1px solid #BFDBFE; color: #1E40AF; }
.vac-notice.amber { background: #FFFBEB; border: 1px solid #FDE68A; color: #92400E; }
.vac-notice i { margin-top: 2px; flex-shrink: 0; }
.vac-fee-badge {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, var(--clr-warm), #FFF3EE);
    border: 1px solid rgba(255,107,53,0.2);
    border-radius: 12px;
    padding: 16px 20px;
    margin-bottom: 28px;
}
.vac-fee-label { font-size: 0.9rem; color: var(--clr-gray); display: flex; align-items: center; gap: 8px; }
.vac-fee-amount { font-size: 1.6rem; font-weight: 800; color: var(--clr-primary); }

/* ── Form section titles ── */
.vac-form-section {
    border-top: 1px solid var(--clr-border);
    padding-top: 24px;
    margin-top: 8px;
    margin-bottom: 20px;
}
.vac-form-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--clr-gray);
    margin-bottom: 18px;
}
.vac-form-section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--clr-border);
}

/* ── Form fields ── */
.vac-field { margin-bottom: 20px; }
.vac-field label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.83rem;
    font-weight: 600;
    color: var(--clr-secondary);
    margin-bottom: 7px;
}
.vac-field label .req { color: var(--clr-primary); font-size: 0.9rem; line-height: 1; }
.vac-field label .tag {
    font-size: 0.72rem;
    font-weight: 500;
    background: #EFF6FF;
    color: #3B82F6;
    padding: 1px 7px;
    border-radius: 99px;
}
.vac-input {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid var(--clr-border);
    border-radius: 10px;
    font-family: var(--font-body);
    font-size: 0.92rem;
    color: var(--clr-secondary);
    background: #FAFAFA;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    -webkit-appearance: none;
    appearance: none;
}
.vac-input:focus {
    outline: none;
    border-color: var(--clr-primary);
    box-shadow: 0 0 0 3.5px rgba(255,107,53,0.12);
    background: #fff;
}
select.vac-input {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 36px;
    cursor: pointer;
}
.vac-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.vac-input-group {
    display: flex;
    gap: 10px;
    align-items: flex-start;
}
.vac-input-group .vac-input { flex: 1; }

/* ── OTP row ── */
#v_otpRow { display: none; }
.otp-input {
    letter-spacing: 8px;
    font-size: 1.3rem;
    text-align: center;
    font-weight: 700;
}
#v_otpMsg { font-size: 0.82rem; margin-top: 6px; display: block; }

/* ── Verified badge ── */
#v_verifiedBadge {
    display: none;
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    padding: 11px 16px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 0.88rem;
    font-weight: 600;
    color: #065F46;
    align-items: center;
    gap: 8px;
}

/* ── Error box ── */
#v_bookingError {
    display: none;
    background: #FEF2F2;
    color: #991B1B;
    padding: 14px 18px;
    border-radius: 10px;
    border: 1px solid #FECACA;
    margin-bottom: 20px;
    font-size: 0.88rem;
    line-height: 1.5;
}

/* ── Submit button ── */
.vac-submit-btn {
    width: 100%;
    justify-content: center;
    padding: 15px 24px;
    font-size: 1rem;
    border-radius: 12px;
    font-weight: 700;
    letter-spacing: 0.02em;
}

/* ── Success state ── */
#vac-step2 {
    display: none;
    text-align: center;
    padding: 56px 24px;
}
.vac-success-icon {
    width: 88px;
    height: 88px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    font-size: 2.5rem;
    color: #059669;
    box-shadow: 0 0 0 12px rgba(5,150,105,0.08);
}
#vac-step2 h3 {
    font-family: var(--font-display);
    font-size: 1.6rem;
    color: var(--clr-secondary);
    margin-bottom: 10px;
}
#vac-step2 p { color: var(--clr-gray); max-width: 340px; margin: 0 auto 28px; line-height: 1.6; }

/* ═══════════════════════════════════════════
   SIDEBAR
═══════════════════════════════════════════ */
.vac-sidebar { display: flex; flex-direction: column; gap: 24px; }

/* Why Vaccinate card */
.why-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    overflow: hidden;
}
.why-card-header {
    padding: 22px 26px 18px;
    border-bottom: 1px solid var(--clr-border);
    display: flex;
    align-items: center;
    gap: 12px;
}
.why-card-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--clr-warm);
    color: var(--clr-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}
.why-card-header h3 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--clr-secondary);
    margin: 0;
}
.why-list { padding: 20px 26px; display: flex; flex-direction: column; gap: 16px; }
.why-item {
    display: flex;
    gap: 14px;
    align-items: flex-start;
}
.why-item-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 0.95rem;
}
.why-item-icon.orange { background: var(--clr-warm); color: var(--clr-primary); }
.why-item-icon.green  { background: #ECFDF5; color: #059669; }
.why-item-icon.blue   { background: #EFF6FF; color: #3B82F6; }
.why-item-icon.purple { background: #F5F3FF; color: #7C3AED; }
.why-item-text h4 { font-size: 0.9rem; margin-bottom: 3px; color: var(--clr-secondary); }
.why-item-text p { font-size: 0.82rem; color: var(--clr-gray); line-height: 1.55; margin: 0; }

/* Stats strip */
.vac-stats {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    padding: 20px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
.vac-stat {
    background: var(--clr-warm);
    border-radius: 12px;
    padding: 16px;
    text-align: center;
}
.vac-stat-num { font-size: 1.5rem; font-weight: 800; color: var(--clr-primary); font-family: var(--font-display); }
.vac-stat-label { font-size: 0.72rem; color: var(--clr-gray); font-weight: 600; margin-top: 2px; }

/* Help card */
.help-card {
    background: linear-gradient(135deg, var(--clr-secondary) 0%, #2D1B69 100%);
    border-radius: 20px;
    padding: 28px;
    color: white;
}
.help-card h3 { font-size: 1.05rem; margin-bottom: 10px; color: #fff; display: flex; align-items: center; gap: 8px; }
.help-card p { opacity: 0.75; font-size: 0.88rem; line-height: 1.55; margin-bottom: 20px; }
.btn-white-outline {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px 20px;
    border: 1.5px solid rgba(255,255,255,0.35);
    border-radius: 10px;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: background 0.2s, border-color 0.2s;
    margin-bottom: 10px;
}
.btn-white-outline:hover { background: rgba(255,255,255,0.12); border-color: rgba(255,255,255,0.6); color: white; }
.btn-whatsapp {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 12px 20px;
    background: #25D366;
    border: none;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    transition: background 0.2s;
}
.btn-whatsapp:hover { background: #1DAA52; color: white; }

/* ═══════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════ */
@media (max-width: 960px) {
    .vac-grid {
        grid-template-columns: 1fr;
    }
    .vac-sidebar {
        /* Show sidebar before form on mobile */
        order: -1;
    }
    .vac-sidebar .why-card { display: none; } /* hide on mobile to save space */
    .vac-hero-visual { display: none; }
    .vac-hero-inner { grid-template-columns: 1fr; }
    .vac-trust-list { gap: 20px 32px; }
}
@media (max-width: 640px) {
    .vac-hero { padding: 48px 0 56px; }
    .vac-card-body { padding: 20px; }
    .vac-row { grid-template-columns: 1fr; gap: 0; }
    .vac-page { padding: 32px 0 60px; }
    .vac-card-header { padding: 22px 20px; }
    .vac-stats { grid-template-columns: 1fr 1fr; }
    .vac-trust-list { gap: 14px 24px; }
    .vac-trust-item span { display: none; }
    .vac-input { font-size: 16px; } /* prevent iOS zoom */
    .vac-input-group { flex-direction: column; }
    .vac-input-group .vac-input { width: 100%; }
    #v_sendOtpBtn { width: 100%; justify-content: center; }
}
@media (max-width: 480px) {
    .vac-steps { display: none; }
    .vac-form-section-title { font-size: 0.72rem; }
}
</style>
@endsection

@section('content')
<div class="page-hero">
                <div class="vac-hero-badge"><i class="fas fa-syringe"></i> Trusted Pet Care</div>
    <div class="container">
         <h1>Book Vaccination & Deworming<br>for Your Pet</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> / Vaccination Booking
        </div>
    </div>
</div>


{{-- ═══ TRUST BAR ═══ --}}
<div class="vac-trust-bar">
    <div class="container">
        <div class="vac-trust-list">
            <div class="vac-trust-item orange">
                <i class="fas fa-shield-alt"></i>
                <span>Certified Vets</span>
            </div>
            <div class="vac-trust-item green">
                <i class="fas fa-clock"></i>
                <span>24h Confirmation</span>
            </div>
            <div class="vac-trust-item blue">
                <i class="fas fa-map-marker-alt"></i>
                <span>Multiple Cities</span>
            </div>
            <div class="vac-trust-item purple">
                <i class="fas fa-heart"></i>
                <span>1000+ Happy Pets</span>
            </div>
        </div>
    </div>
</div>

{{-- ═══ MAIN CONTENT ═══ --}}
<div class="vac-page">
    <div class="container">
        <div class="vac-grid">

            {{-- ──────── BOOKING FORM CARD ──────── --}}
            <div class="vac-card fade-up">
                <div class="vac-card-header">
                    <h2><i class="fas fa-calendar-check" style="margin-right:10px;"></i>Schedule an Appointment</h2>
                    <p>Complete the form below to request your vaccination booking</p>
                </div>
                <div class="vac-card-body">

                    {{-- Step indicator --}}
                    <div class="vac-steps" id="vacStepIndicator">
                        <div class="vac-step active" id="stepDot1">
                            <div class="vac-step-num">1</div>
                            <div class="vac-step-label">Your Details</div>
                        </div>
                        <div class="vac-step-line"></div>
                        <div class="vac-step" id="stepDot2">
                            <div class="vac-step-num">2</div>
                            <div class="vac-step-label">Pet Info</div>
                        </div>
                        <div class="vac-step-line"></div>
                        <div class="vac-step" id="stepDot3">
                            <div class="vac-step-num">3</div>
                            <div class="vac-step-label">Confirm</div>
                        </div>
                    </div>

                    {{-- ── STEP 1: BOOKING FORM ── --}}
                    <div id="vac-step1">

                        {{-- OTP notice --}}
                        @if ($otpRequired)
                        <div class="vac-notice blue">
                            <i class="fas fa-shield-alt"></i>
                            <span>We verify your phone number via <strong>WhatsApp OTP</strong> before confirming your booking.</span>
                        </div>
                        @endif

                        {{-- Booking fee --}}
                        @if ($paymentEnabled && $bookingFee > 0)
                        <div class="vac-fee-badge">
                            <div class="vac-fee-label"><i class="fas fa-credit-card" style="color:var(--clr-primary);"></i> Booking Fee (refundable on visit)</div>
                            <div class="vac-fee-amount">₹{{ number_format($bookingFee) }}</div>
                        </div>
                        @endif

                        {{-- ── SECTION: Contact Info ── --}}
                        <div class="vac-form-section">
                            <div class="vac-form-section-title"><i class="fas fa-user"></i> Contact Information</div>
                        </div>

                        <div class="vac-field">
                            <label>Full Name <span class="req">*</span></label>
                            <input type="text" id="v_name" class="vac-input" placeholder="e.g. Rahul Sharma" required>
                        </div>

                        <div class="vac-row">
                            <div class="vac-field">
                                <label>
                                    Phone Number <span class="req">*</span>
                                    @if ($otpRequired)<span class="tag">WhatsApp</span>@endif
                                </label>
                                @if ($otpRequired)
                                    <div class="vac-input-group">
                                        <input type="tel" id="v_phone" class="vac-input" placeholder="9876543210" required>
                                        <button id="v_sendOtpBtn" onclick="vacSendOtp()" class="btn btn-primary" style="padding:11px 16px;white-space:nowrap;font-size:0.85rem;border-radius:10px;flex-shrink:0;">
                                            <i class="fas fa-paper-plane"></i> Send OTP
                                        </button>
                                    </div>
                                @else
                                    <input type="tel" id="v_phone" class="vac-input" placeholder="9876543210" required>
                                @endif
                            </div>
                            <div class="vac-field">
                                <label>Email Address <span class="req">*</span></label>
                                <input type="email" id="v_email" class="vac-input" placeholder="you@example.com" required>
                            </div>
                        </div>

                        {{-- OTP input --}}
                        @if ($otpRequired)
                        <div id="v_otpRow" class="vac-field">
                            <label>Enter OTP <span class="req">*</span></label>
                            <div class="vac-input-group">
                                <input type="text" id="v_otp" class="vac-input otp-input" placeholder="● ● ● ● ● ●" maxlength="6">
                                <button onclick="vacVerifyOtp()" class="btn btn-outline" style="padding:11px 18px;font-size:0.85rem;border-radius:10px;flex-shrink:0;white-space:nowrap;">
                                    <i class="fas fa-check"></i> Verify
                                </button>
                            </div>
                            <small id="v_otpMsg"></small>
                        </div>
                        @endif

                        {{-- Verified badge (only relevant when OTP is enabled) --}}
                        @if ($otpRequired)
                        <div id="v_verifiedBadge" style="display:none;">
                            <i class="fas fa-check-circle" style="font-size:1.1rem;flex-shrink:0;"></i>
                            Phone number verified successfully!
                        </div>
                        @endif

                        {{-- ── SECTION: Pet Details ── --}}
                        <div class="vac-form-section">
                            <div class="vac-form-section-title"><i class="fas fa-paw"></i> Pet Details</div>
                        </div>

                        <div class="vac-row">
                            <div class="vac-field">
                                <label>Pet Type <span class="req">*</span></label>
                                <select id="v_pet_type" class="vac-input" required>
                                    <option value="">Select Pet Type</option>
                                    @foreach ($petTypes as $petType)
                                        <option value="{{ $petType->name }}">
                                            {{ $petType->icon }} {{ $petType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vac-field">
                                <label>Pet Name <span class="req">*</span></label>
                                <input type="text" id="v_pet_name" class="vac-input" placeholder="e.g. Buddy, Max" required>
                            </div>
                        </div>

                        {{-- ── SECTION: Appointment ── --}}
                        <div class="vac-form-section">
                            <div class="vac-form-section-title"><i class="fas fa-calendar-alt"></i> Appointment Details</div>
                        </div>

                        <div class="vac-row">
                            <div class="vac-field">
                                <label>Vaccination Type <span class="req">*</span></label>
                                <select id="v_vaccination_type_id" class="vac-input" required>
                                    <option value="">Select Vaccination</option>
                                    @foreach ($vaccinationTypes as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->name }}
                                            {{-- @if ($type->price) — ₹{{ number_format($type->price) }}@endif --}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="vac-field">
                                <label>Preferred Date <span class="req">*</span></label>
                                <input type="date" id="v_preferred_date" class="vac-input"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                        </div>

                        <div class="vac-field">
                            <label>City <span class="req">*</span></label>
                            <select id="v_city_id" class="vac-input" required>
                                <option value="">Select Your City</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="vac-field">
                            <label>Additional Notes <span style="font-weight:400;color:var(--clr-gray);font-size:0.78rem;">(optional)</span></label>
                            <textarea id="v_message" class="vac-input" rows="3" placeholder="Any special requirements, health conditions, or notes about your pet..." style="resize:vertical;min-height:90px;"></textarea>
                        </div>

                        @if(\App\Services\RecaptchaService::isEnabled())
                        <div id="v_recaptcha" class="g-recaptcha" data-sitekey="{{ \App\Services\RecaptchaService::getSiteKey() }}" style="margin-bottom:20px;"></div>
                        @endif

                        {{-- Error --}}
                        <div id="v_bookingError"></div>

                        <button id="v_submitBtn" onclick="vacSubmit()" class="btn btn-primary vac-submit-btn">
                            <i class="fas fa-calendar-check"></i>
                            @if ($paymentEnabled && $bookingFee > 0)
                                Proceed to Payment — ₹{{ number_format($bookingFee) }}
                            @else
                                Book My Appointment
                            @endif
                        </button>

                        <p style="text-align:center;font-size:0.78rem;color:var(--clr-gray);margin-top:14px;">
                            <i class="fas fa-lock" style="font-size:0.7rem;"></i>
                            Your information is secure and will not be shared.
                        </p>
                    </div>

                    {{-- ── STEP 2: SUCCESS ── --}}
                    <div id="vac-step2">
                        <div class="vac-success-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <h3>Booking Confirmed!</h3>
                        <p>Thank you! We'll contact you within 24 hours to confirm your vaccination appointment details.</p>
                        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                            <a href="{{ route('home') }}" class="btn btn-outline"><i class="fas fa-home"></i> Back to Home</a>
                            <a href="{{ route('vaccination') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Book Another</a>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ──────── SIDEBAR ──────── --}}
            <div class="vac-sidebar fade-up" style="animation-delay:0.15s;">

                {{-- Stats --}}
                <div class="vac-stats">
                    <div class="vac-stat">
                        <div class="vac-stat-num">1000+</div>
                        <div class="vac-stat-label">Pets Vaccinated</div>
                    </div>
                    <div class="vac-stat">
                        <div class="vac-stat-num">50+</div>
                        <div class="vac-stat-label">Vaccine Types</div>
                    </div>
                    <div class="vac-stat">
                        <div class="vac-stat-num">{{ $cities->count() }}+</div>
                        <div class="vac-stat-label">Cities Covered</div>
                    </div>
                    <div class="vac-stat">
                        <div class="vac-stat-num">24h</div>
                        <div class="vac-stat-label">Confirmation</div>
                    </div>
                </div>

                {{-- Why Vaccinate --}}
                <div class="why-card">
                    <div class="why-card-header">
                        <div class="why-card-icon"><i class="fas fa-syringe"></i></div>
                        <h3>Why Vaccinate Your Pet?</h3>
                    </div>
                    <div class="why-list">
                        <div class="why-item">
                            <div class="why-item-icon orange"><i class="fas fa-shield-alt"></i></div>
                            <div class="why-item-text">
                                <h4>Disease Prevention</h4>
                                <p>Protect your pet from deadly diseases like rabies, distemper, and parvovirus.</p>
                            </div>
                        </div>
                        <div class="why-item">
                            <div class="why-item-icon green"><i class="fas fa-heart"></i></div>
                            <div class="why-item-text">
                                <h4>Longer, Healthier Life</h4>
                                <p>Regular vaccinations help ensure your pet lives a long and healthy life.</p>
                            </div>
                        </div>
                        <div class="why-item">
                            <div class="why-item-icon blue"><i class="fas fa-users"></i></div>
                            <div class="why-item-text">
                                <h4>Public Safety</h4>
                                <p>Vaccinated pets help prevent the spread of diseases to other animals and humans.</p>
                            </div>
                        </div>
                        <div class="why-item">
                            <div class="why-item-icon purple"><i class="fas fa-certificate"></i></div>
                            <div class="why-item-text">
                                <h4>Vaccination Record</h4>
                                <p>You receive an official vaccination record certificate for your pet after each visit.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Help card --}}
                <div class="help-card">
                    <h3><i class="fas fa-headset"></i> Need Help?</h3>
                    <p>Our team is available to answer your questions about pet vaccinations and help you choose the right schedule.</p>
                    @if ($settings['site_phone'])
                    <a href="tel:{{ $settings['site_phone'] }}" class="btn-white-outline">
                        <i class="fas fa-phone"></i> {{ $settings['site_phone'] }}
                    </a>
                    @endif
                    @if ($settings['whatsapp_number'])
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings['whatsapp_number']) }}" target="_blank" rel="noopener" class="btn-whatsapp">
                        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                    </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@if ($paymentEnabled && $bookingFee > 0)
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif

@section('scripts')
@if(\App\Services\RecaptchaService::isEnabled())
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif
<script>
    const VAC_CSRF        = '{{ csrf_token() }}';
    const VAC_OTP_REQUIRED = {{ $otpRequired ? 'true' : 'false' }};
    const VAC_PAY_ENABLED  = {{ $paymentEnabled ? 'true' : 'false' }};
    const VAC_BOOKING_FEE  = {{ $bookingFee }};
    const VAC_RAZORPAY_KEY = '{{ $razorpayKey }}';
    const VAC_SITE_NAME    = '{{ $settings['site_name'] }}';
    let vacOtpVerified = false;

    function showVacError(msg) {
        const el = document.getElementById('v_bookingError');
        el.textContent = msg;
        el.style.display = 'block';
    }
    function hideVacError() {
        document.getElementById('v_bookingError').style.display = 'none';
    }

    async function vacSendOtp() {
        const name  = document.getElementById('v_name').value.trim();
        const phone = document.getElementById('v_phone').value.trim();
        if (!name)  { alert('Please enter your name first.'); return; }
        if (!phone) { alert('Please enter your phone number.'); return; }

        const btn = document.getElementById('v_sendOtpBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending…';

        try {
            const res  = await fetch('{{ route('vaccination.send-otp') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': VAC_CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ name, phone }),
            });
            const data = await res.json();
            if (data.success) {
                document.getElementById('v_otpRow').style.display = 'block';
                const msg = document.getElementById('v_otpMsg');
                msg.textContent = data.message;
                msg.style.color = 'var(--clr-accent)';
            } else {
                alert(data.message || 'Failed to send OTP.');
            }
        } catch (e) {
            alert('Network error. Please try again.');
        }

        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane"></i> Resend OTP';
    }

    async function vacVerifyOtp() {
        const phone = document.getElementById('v_phone').value.trim();
        const otp   = document.getElementById('v_otp').value.trim();
        if (!otp || otp.length !== 6) { alert('Please enter the 6-digit OTP.'); return; }

        const btn = event.target;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        const msg = document.getElementById('v_otpMsg');

        try {
            const res  = await fetch('{{ route('vaccination.verify-otp') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': VAC_CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ phone, otp }),
            });
            const data = await res.json();
            if (data.success) {
                vacOtpVerified = true;
                document.getElementById('v_otpRow').style.display = 'none';
                const badge = document.getElementById('v_verifiedBadge');
                badge.style.display = 'flex';
                msg.textContent = '';
                // advance step indicator
                document.getElementById('stepDot2').classList.add('active');
            } else {
                msg.textContent = data.message || 'Invalid OTP.';
                msg.style.color = '#EF4444';
            }
        } catch (e) {
            msg.textContent = 'Network error. Please try again.';
            msg.style.color = '#EF4444';
        }

        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check"></i> Verify';
    }

    async function vacSubmit() {
        if (VAC_OTP_REQUIRED && !vacOtpVerified) {
            alert('Please verify your phone number first.');
            return;
        }

        const payload = {
            name:                 document.getElementById('v_name').value.trim(),
            phone:                document.getElementById('v_phone').value.trim(),
            email:                document.getElementById('v_email').value.trim(),
            pet_type:             document.getElementById('v_pet_type').value,
            pet_name:             document.getElementById('v_pet_name').value.trim(),
            vaccination_type_id:  document.getElementById('v_vaccination_type_id').value,
            preferred_date:       document.getElementById('v_preferred_date').value,
            city_id:              document.getElementById('v_city_id').value,
            message:              document.getElementById('v_message').value.trim(),
            g_recaptcha_response: (typeof grecaptcha !== 'undefined' && document.getElementById('v_recaptcha'))
                                    ? grecaptcha.getResponse() : '',
        };

        if (!payload.name || !payload.phone || !payload.email || !payload.pet_type ||
            !payload.pet_name || !payload.vaccination_type_id || !payload.preferred_date || !payload.city_id) {
            showVacError('Please fill in all required fields.');
            return;
        }

        const btn = document.getElementById('v_submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing…';
        hideVacError();

        // advance step
        document.getElementById('stepDot3').classList.add('active');

        try {
            const res  = await fetch('{{ route('vaccination.store') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': VAC_CSRF, 'Accept': 'application/json' },
                body: JSON.stringify(payload),
            });
            const data = await res.json();

            if (!data.success) {
                showVacError(data.message || 'Booking failed. Please try again.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-calendar-check"></i> Book My Appointment';
                document.getElementById('stepDot3').classList.remove('active');
                return;
            }

            if (data.requires_payment) {
                vacInitRazorpay(data);
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-credit-card"></i> Proceed to Payment';
                return;
            }

            // Success
            document.getElementById('vac-step1').style.display = 'none';
            document.getElementById('vacStepIndicator').style.display = 'none';
            document.getElementById('vac-step2').style.display = 'block';

        } catch (e) {
            showVacError('Unable to submit booking. Please check your connection and try again.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-calendar-check"></i> Book My Appointment';
            document.getElementById('stepDot3').classList.remove('active');
        }
    }

    function vacInitRazorpay(data) {
        const options = {
            key:         VAC_RAZORPAY_KEY,
            amount:      data.amount,
            currency:    data.currency,
            order_id:    data.order_id,
            name:        VAC_SITE_NAME,
            description: 'Vaccination Booking Fee',
            prefill: {
                name:    data.customer_name,
                email:   data.customer_email,
                contact: data.customer_phone,
            },
            theme: { color: '#FF6B35' },
            handler: async function(response) {
                try {
                    const vRes  = await fetch('{{ route('vaccination.verify-payment') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': VAC_CSRF, 'Accept': 'application/json' },
                        body: JSON.stringify({
                            booking_id:           data.booking_id,
                            razorpay_order_id:    response.razorpay_order_id,
                            razorpay_payment_id:  response.razorpay_payment_id,
                            razorpay_signature:   response.razorpay_signature,
                        }),
                    });
                    const vData = await vRes.json();
                    if (vData.success) {
                        document.getElementById('vac-step1').style.display = 'none';
                        document.getElementById('vacStepIndicator').style.display = 'none';
                        document.getElementById('vac-step2').style.display = 'block';
                    } else {
                        showVacError(vData.message || 'Payment verification failed.');
                    }
                } catch (e) {
                    showVacError('Payment verification failed. Please contact support.');
                }
            },
            modal: { ondismiss: function() {} },
        };
        new Razorpay(options).open();
    }
</script>
@endsection
@endsection
