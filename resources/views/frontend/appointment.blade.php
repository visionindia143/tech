@extends('layouts.frontend')

@section('meta_title', 'Book an Appointment - ' . $settings['site_name'])
@section('meta_description',
    'Book a service appointment for your pet. Choose from our expert pet care services
    available across multiple cities.')

@section('styles')
    <style>
        /* ═══════════════════════════════════════════
                                       APPOINTMENT PAGE STYLES
                                    ═══════════════════════════════════════════ */

        /* ── Trust bar ── */
        .appt-trust-bar {
            background: white;
            border-bottom: 1px solid var(--clr-border);
            padding: 18px 0;
        }

        .appt-trust-list {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }

        .appt-trust-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--clr-secondary);
        }

        .appt-trust-item i {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .appt-trust-item.orange i {
            background: var(--clr-warm);
            color: var(--clr-primary);
        }

        .appt-trust-item.green i {
            background: #ECFDF5;
            color: #059669;
        }

        .appt-trust-item.blue i {
            background: #EFF6FF;
            color: #3B82F6;
        }

        .appt-trust-item.purple i {
            background: #F5F3FF;
            color: #7C3AED;
        }

        /* ── Page layout ── */
        .appt-page {
            padding: 60px 0 80px;
            background: #F8F9FC;
        }

        .appt-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 32px;
            align-items: start;
        }

        /* ── Form card ── */
        .appt-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .appt-card-header {
            background: linear-gradient(135deg, var(--clr-primary), #FF8C5A);
            padding: 28px 32px;
            color: white;
        }

        .appt-card-header h2 {
            font-family: var(--font-display);
            font-size: 1.5rem;
            margin-bottom: 6px;
            color: #fff;
        }

        .appt-card-header p {
            opacity: 0.85;
            font-size: 0.9rem;
        }

        .appt-card-body {
            padding: 32px;
        }

        /* ── Step indicator ── */
        .appt-steps {
            display: flex;
            align-items: center;
            margin-bottom: 32px;
            gap: 0;
        }

        .appt-step {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .appt-step-num {
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

        .appt-step.active .appt-step-num {
            background: var(--clr-primary);
            color: white;
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.2);
        }

        .appt-step-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--clr-gray);
        }

        .appt-step.active .appt-step-label {
            color: var(--clr-primary);
        }

        .appt-step-line {
            flex: 1;
            height: 2px;
            background: var(--clr-border);
            margin: 0 8px;
        }

        /* ── Notices ── */
        .appt-notice {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.88rem;
            line-height: 1.5;
        }

        .appt-notice.blue {
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            color: #1E40AF;
        }

        .appt-notice.amber {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            color: #92400E;
        }

        .appt-notice i {
            margin-top: 2px;
            flex-shrink: 0;
        }

        .appt-fee-badge {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, var(--clr-warm), #FFF3EE);
            border: 1px solid rgba(255, 107, 53, 0.2);
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 28px;
        }

        .appt-fee-label {
            font-size: 0.9rem;
            color: var(--clr-gray);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .appt-fee-amount {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--clr-primary);
        }

        /* ── Form section titles ── */
        .appt-form-section {
            border-top: 1px solid var(--clr-border);
            padding-top: 24px;
            margin-top: 8px;
            margin-bottom: 20px;
        }

        .appt-form-section-title {
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

        .appt-form-section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--clr-border);
        }

        /* ── Form fields ── */
        .appt-field {
            margin-bottom: 20px;
        }

        .appt-field label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.83rem;
            font-weight: 600;
            color: var(--clr-secondary);
            margin-bottom: 7px;
        }

        .appt-field label .req {
            color: var(--clr-primary);
            font-size: 0.9rem;
            line-height: 1;
        }

        .appt-field label .tag {
            font-size: 0.72rem;
            font-weight: 500;
            background: #EFF6FF;
            color: #3B82F6;
            padding: 1px 7px;
            border-radius: 99px;
        }

        .appt-input {
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

        .appt-input:focus {
            outline: none;
            border-color: var(--clr-primary);
            box-shadow: 0 0 0 3.5px rgba(255, 107, 53, 0.12);
            background: #fff;
        }

        select.appt-input {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
            cursor: pointer;
        }

        .appt-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .appt-input-group {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .appt-input-group .appt-input {
            flex: 1;
        }

        /* ── OTP row ── */
        #a_otpRow {
            display: none;
        }

        .otp-input {
            letter-spacing: 8px;
            font-size: 1.3rem;
            text-align: center;
            font-weight: 700;
        }

        #a_otpMsg {
            font-size: 0.82rem;
            margin-top: 6px;
            display: block;
        }

        /* ── Verified badge ── */
        #a_verifiedBadge {
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
        #a_bookingError {
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
        .appt-submit-btn {
            width: 100%;
            justify-content: center;
            padding: 15px 24px;
            font-size: 1rem;
            border-radius: 12px;
            font-weight: 700;
            letter-spacing: 0.02em;
        }

        /* ── Success state ── */
        #appt-step2 {
            display: none;
            text-align: center;
            padding: 56px 24px;
        }

        .appt-success-icon {
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
            box-shadow: 0 0 0 12px rgba(5, 150, 105, 0.08);
        }

        #appt-step2 h3 {
            font-family: var(--font-display);
            font-size: 1.6rem;
            color: var(--clr-secondary);
            margin-bottom: 10px;
        }

        #appt-step2 p {
            color: var(--clr-gray);
            max-width: 340px;
            margin: 0 auto 28px;
            line-height: 1.6;
        }

        /* ═══════════════════════════════════════════
                                       SIDEBAR
                                    ═══════════════════════════════════════════ */
        .appt-sidebar {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .why-appt-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .why-appt-card-header {
            padding: 22px 26px 18px;
            border-bottom: 1px solid var(--clr-border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .why-appt-card-icon {
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

        .why-appt-card-header h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--clr-secondary);
            margin: 0;
        }

        .why-appt-list {
            padding: 20px 26px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .why-appt-item {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .why-appt-item-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 0.95rem;
        }

        .why-appt-item-icon.orange {
            background: var(--clr-warm);
            color: var(--clr-primary);
        }

        .why-appt-item-icon.green {
            background: #ECFDF5;
            color: #059669;
        }

        .why-appt-item-icon.blue {
            background: #EFF6FF;
            color: #3B82F6;
        }

        .why-appt-item-icon.purple {
            background: #F5F3FF;
            color: #7C3AED;
        }

        .why-appt-item-text h4 {
            font-size: 0.9rem;
            margin-bottom: 3px;
            color: var(--clr-secondary);
        }

        .why-appt-item-text p {
            font-size: 0.82rem;
            color: var(--clr-gray);
            line-height: 1.55;
            margin: 0;
        }

        .appt-stats {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .appt-stat {
            background: var(--clr-warm);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
        }

        .appt-stat-num {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--clr-primary);
            font-family: var(--font-display);
        }

        .appt-stat-label {
            font-size: 0.72rem;
            color: var(--clr-gray);
            font-weight: 600;
            margin-top: 2px;
        }

        .help-appt-card {
            background: linear-gradient(135deg, var(--clr-secondary) 0%, #2D1B69 100%);
            border-radius: 20px;
            padding: 28px;
            color: white;
        }

        .help-appt-card h3 {
            font-size: 1.05rem;
            margin-bottom: 10px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .help-appt-card p {
            opacity: 0.75;
            font-size: 0.88rem;
            line-height: 1.55;
            margin-bottom: 20px;
        }

        .btn-white-outline {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px 20px;
            border: 1.5px solid rgba(255, 255, 255, 0.35);
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: background 0.2s, border-color 0.2s;
            margin-bottom: 10px;
        }

        .btn-white-outline:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, 0.6);
            color: white;
        }

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

        .btn-whatsapp:hover {
            background: #1DAA52;
            color: white;
        }

        /* ═══════════════════════════════════════════
                                       RESPONSIVE
                                    ═══════════════════════════════════════════ */
        @media (max-width: 960px) {
            .appt-grid {
                grid-template-columns: 1fr;
            }

            .appt-sidebar {
                order: -1;
            }

            .appt-sidebar .why-appt-card {
                display: none;
            }

            .appt-trust-list {
                gap: 20px 32px;
            }
        }

        @media (max-width: 640px) {
            .appt-card-body {
                padding: 20px;
            }

            .appt-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .appt-page {
                padding: 32px 0 60px;
            }

            .appt-card-header {
                padding: 22px 20px;
            }

            .appt-stats {
                grid-template-columns: 1fr 1fr;
            }

            .appt-trust-list {
                gap: 14px 24px;
            }

            .appt-trust-item span {
                display: none;
            }

            .appt-input {
                font-size: 16px;
            }

            .appt-input-group {
                flex-direction: column;
            }

            .appt-input-group .appt-input {
                width: 100%;
            }

            #a_sendOtpBtn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .appt-steps {
                display: none;
            }

            .appt-form-section-title {
                font-size: 0.72rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-hero">
        <div class="container">
            <div
                style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,107,53,0.15);border:1px solid rgba(255,107,53,0.3);color:var(--clr-primary);font-size:0.8rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:6px 14px;border-radius:999px;margin-bottom:18px;">
                <i class="fas fa-calendar-check"></i> Expert Pet Care
            </div>
            <h1>Book an Appointment<br>for Your Pet</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a> / Appointment Booking
            </div>
        </div>
    </div>

    {{-- ═══ TRUST BAR ═══ --}}
    <div class="appt-trust-bar">
        <div class="container">
            <div class="appt-trust-list">
                <div class="appt-trust-item orange">
                    <i class="fas fa-user-md"></i>
                    <span>Expert Vets</span>
                </div>
                <div class="appt-trust-item green">
                    <i class="fas fa-clock"></i>
                    <span>24h Confirmation</span>
                </div>
                <div class="appt-trust-item blue">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Multiple Cities</span>
                </div>
                <div class="appt-trust-item purple">
                    <i class="fas fa-heart"></i>
                    <span>1000+ Happy Pets</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ MAIN CONTENT ═══ --}}
    <div class="appt-page">
        <div class="container">
            <div class="appt-grid">

                {{-- ──────── BOOKING FORM CARD ──────── --}}
                <div class="appt-card fade-up">
                    <div class="appt-card-header">
                        <h2><i class="fas fa-calendar-check" style="margin-right:10px;"></i>Schedule an Appointment</h2>
                        <p>Complete the form below to request your appointment booking</p>
                    </div>
                    <div class="appt-card-body">

                        {{-- Step indicator --}}
                        <div class="appt-steps" id="apptStepIndicator">
                            <div class="appt-step active" id="aStepDot1">
                                <div class="appt-step-num">1</div>
                                <div class="appt-step-label">Your Details</div>
                            </div>
                            <div class="appt-step-line"></div>
                            <div class="appt-step" id="aStepDot2">
                                <div class="appt-step-num">2</div>
                                <div class="appt-step-label">Pet Info</div>
                            </div>
                            <div class="appt-step-line"></div>
                            <div class="appt-step" id="aStepDot3">
                                <div class="appt-step-num">3</div>
                                <div class="appt-step-label">Confirm</div>
                            </div>
                        </div>

                        {{-- ── STEP 1: BOOKING FORM ── --}}
                        <div id="appt-step1">

                            @if ($otpRequired)
                                <div class="appt-notice blue">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>We verify your phone number via <strong>WhatsApp OTP</strong> before confirming
                                        your booking.</span>
                                </div>
                            @endif

                            @if ($paymentEnabled && $bookingFee > 0)
                                <div class="appt-fee-badge">
                                    <div class="appt-fee-label"><i class="fas fa-credit-card"
                                            style="color:var(--clr-primary);"></i> Booking Fee (refundable on visit)</div>
                                    <div class="appt-fee-amount">₹{{ number_format($bookingFee) }}</div>
                                </div>
                            @endif

                            {{-- ── SECTION: Contact Info ── --}}
                            <div class="appt-form-section">
                                <div class="appt-form-section-title"><i class="fas fa-user"></i> Contact Information</div>
                            </div>

                            <div class="appt-field">
                                <label>Full Name <span class="req">*</span></label>
                                <input type="text" id="a_name" class="appt-input" placeholder="e.g. Rahul Sharma"
                                    required>
                            </div>

                            <div class="appt-row">
                                <div class="appt-field">
                                    <label>
                                        Phone Number <span class="req">*</span>
                                        @if ($otpRequired)
                                            <span class="tag">WhatsApp</span>
                                        @endif
                                    </label>
                                    @if ($otpRequired)
                                        <div class="appt-input-group">
                                            <input type="tel" id="a_phone" class="appt-input" placeholder="9876543210"
                                                required>
                                            <button id="a_sendOtpBtn" onclick="apptSendOtp()" class="btn btn-primary"
                                                style="padding:11px 16px;white-space:nowrap;font-size:0.85rem;border-radius:10px;flex-shrink:0;">
                                                <i class="fas fa-paper-plane"></i> Send OTP
                                            </button>
                                        </div>
                                    @else
                                        <input type="tel" id="a_phone" class="appt-input" placeholder="9876543210"
                                            required>
                                    @endif
                                </div>
                                <div class="appt-field">
                                    <label>Email Address <span class="req">*</span></label>
                                    <input type="email" id="a_email" class="appt-input" placeholder="you@example.com"
                                        required>
                                </div>
                            </div>

                            @if ($otpRequired)
                                <div id="a_otpRow" class="appt-field">
                                    <label>Enter OTP <span class="req">*</span></label>
                                    <div class="appt-input-group">
                                        <input type="text" id="a_otp" class="appt-input otp-input"
                                            placeholder="● ● ● ● ● ●" maxlength="6">
                                        <button onclick="apptVerifyOtp()" class="btn btn-outline"
                                            style="padding:11px 18px;font-size:0.85rem;border-radius:10px;flex-shrink:0;white-space:nowrap;">
                                            <i class="fas fa-check"></i> Verify
                                        </button>
                                    </div>
                                    <small id="a_otpMsg"></small>
                                </div>
                            @endif

                            @if ($otpRequired)
                                <div id="a_verifiedBadge" style="display:none;">
                                    <i class="fas fa-check-circle" style="font-size:1.1rem;flex-shrink:0;"></i>
                                    Phone number verified successfully!
                                </div>
                            @endif

                            {{-- ── SECTION: Pet Details ── --}}
                            <div class="appt-form-section">
                                <div class="appt-form-section-title"><i class="fas fa-paw"></i> Pet Details</div>
                            </div>

                            <div class="appt-row">
                                <div class="appt-field">
                                    <label>Pet Type <span class="req">*</span></label>
                                    <select id="a_pet_type" class="appt-input" required>
                                        <option value="">Select Pet Type</option>
                                        @foreach ($petTypes as $petType)
                                            <option value="{{ $petType->name }}">
                                                {{ $petType->icon }} {{ $petType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="appt-field">
                                    <label>Pet Name <span class="req">*</span></label>
                                    <input type="text" id="a_pet_name" class="appt-input"
                                        placeholder="e.g. Buddy, Max" required>
                                </div>
                            </div>

                            {{-- ── SECTION: Appointment ── --}}
                            <div class="appt-form-section">
                                <div class="appt-form-section-title"><i class="fas fa-calendar-alt"></i> Appointment
                                    Details</div>
                            </div>

                            <div class="appt-row">
                                <div class="appt-field">
                                    <label>Service <span class="req">*</span></label>
                                    {{-- <select id="a_service_id" class="appt-input" required>
                                        <option value="">Select Service</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}">
                                                {{ $service->title }}
                                                @if ($service->price) — ₹{{ number_format($service->price) }}@endif
                                            </option>
                                        @endforeach
                                    </select> --}}
                                    <select id="a_selection" class="appt-input" required>
                                        <option value="">Select Service / Appointment Type</option>

                                        {{-- Services --}}
                                        @foreach ($services as $service)
                                            <option value="service_{{ $service->id }}">
                                                {{ $service->title }}
                                            </option>
                                        @endforeach

                                        {{-- Appointment Types --}}
                                        @foreach ($apptTypes as $type)
                                            <option value="type_{{ $type->id }}">
                                                {{ $type->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="appt-field">
                                    <label>Preferred Date <span class="req">*</span></label>
                                    <input type="date" id="a_preferred_date" class="appt-input"
                                        min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                </div>
                            </div>

                            <div class="appt-field">
                                <label>City <span class="req">*</span></label>
                                <select id="a_city_id" class="appt-input" required>
                                    <option value="">Select Your City</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="appt-field">
                                <label>Additional Notes <span
                                        style="font-weight:400;color:var(--clr-gray);font-size:0.78rem;">(optional)</span></label>
                                <textarea id="a_message" class="appt-input" rows="3"
                                    placeholder="Any special requirements, health conditions, or notes about your pet..."
                                    style="resize:vertical;min-height:90px;"></textarea>
                            </div>

                            @if (\App\Services\RecaptchaService::isEnabled())
                                <div id="a_recaptcha" class="g-recaptcha"
                                    data-sitekey="{{ \App\Services\RecaptchaService::getSiteKey() }}"
                                    style="margin-bottom:20px;"></div>
                            @endif

                            <div id="a_bookingError"></div>

                            <button id="a_submitBtn" onclick="apptSubmit()" class="btn btn-primary appt-submit-btn">
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
                        <div id="appt-step2">
                            <div class="appt-success-icon">
                                <i class="fas fa-check"></i>
                            </div>
                            <h3>Appointment Confirmed!</h3>
                            <p>Thank you! We'll contact you within 24 hours to confirm your appointment details.</p>
                            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                                <a href="{{ route('home') }}" class="btn btn-outline"><i class="fas fa-home"></i> Back
                                    to Home</a>
                                <a href="{{ route('appointment') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                                    Book Another</a>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ──────── SIDEBAR ──────── --}}
                <div class="appt-sidebar fade-up" style="animation-delay:0.15s;">

                    <div class="appt-stats">
                        <div class="appt-stat">
                            <div class="appt-stat-num">500+</div>
                            <div class="appt-stat-label">Appointments</div>
                        </div>
                        <div class="appt-stat">
                            <div class="appt-stat-num">{{ $services->count() }}+</div>
                            <div class="appt-stat-label">Services</div>
                        </div>
                        <div class="appt-stat">
                            <div class="appt-stat-num">{{ $cities->count() }}+</div>
                            <div class="appt-stat-label">Cities</div>
                        </div>
                        <div class="appt-stat">
                            <div class="appt-stat-num">24h</div>
                            <div class="appt-stat-label">Confirmation</div>
                        </div>
                    </div>

                    <div class="why-appt-card">
                        <div class="why-appt-card-header">
                            <div class="why-appt-card-icon"><i class="fas fa-paw"></i></div>
                            <h3>Why Book With Us?</h3>
                        </div>
                        <div class="why-appt-list">
                            <div class="why-appt-item">
                                <div class="why-appt-item-icon orange"><i class="fas fa-user-md"></i></div>
                                <div class="why-appt-item-text">
                                    <h4>Expert Professionals</h4>
                                    <p>All our services are delivered by certified and experienced pet care professionals.
                                    </p>
                                </div>
                            </div>
                            <div class="why-appt-item">
                                <div class="why-appt-item-icon green"><i class="fas fa-heart"></i></div>
                                <div class="why-appt-item-text">
                                    <h4>Personalised Care</h4>
                                    <p>We tailor every appointment to the specific needs of your pet's breed and condition.
                                    </p>
                                </div>
                            </div>
                            <div class="why-appt-item">
                                <div class="why-appt-item-icon blue"><i class="fas fa-shield-alt"></i></div>
                                <div class="why-appt-item-text">
                                    <h4>Safe & Comfortable</h4>
                                    <p>Our facilities are designed to keep your pet calm, safe, and stress-free throughout.
                                    </p>
                                </div>
                            </div>
                            <div class="why-appt-item">
                                <div class="why-appt-item-icon purple"><i class="fas fa-clock"></i></div>
                                <div class="why-appt-item-text">
                                    <h4>Flexible Scheduling</h4>
                                    <p>Choose your preferred date and we'll do our best to accommodate your schedule.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="help-appt-card">
                        <h3><i class="fas fa-headset"></i> Need Help?</h3>
                        <p>Our team is available to answer your questions about our services and help you choose the right
                            appointment.</p>
                        @if ($settings['site_phone'])
                            <a href="tel:{{ $settings['site_phone'] }}" class="btn-white-outline">
                                <i class="fas fa-phone"></i> {{ $settings['site_phone'] }}
                            </a>
                        @endif
                        @if ($settings['whatsapp_number'])
                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $settings['whatsapp_number']) }}"
                                target="_blank" rel="noopener" class="btn-whatsapp">
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
    @if (\App\Services\RecaptchaService::isEnabled())
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    <script>
        const APPT_CSRF = '{{ csrf_token() }}';
        const APPT_OTP_REQUIRED = {{ $otpRequired ? 'true' : 'false' }};
        const APPT_PAY_ENABLED = {{ $paymentEnabled ? 'true' : 'false' }};
        const APPT_BOOKING_FEE = {{ $bookingFee }};
        const APPT_RAZORPAY_KEY = '{{ $razorpayKey }}';
        const APPT_SITE_NAME = '{{ $settings['site_name'] }}';
        const APPT_SHOW_BASE_URL = '{{ url('/appointment-booking') }}';
        let apptOtpVerified = false;

        function showApptError(msg) {
            const el = document.getElementById('a_bookingError');
            el.textContent = msg;
            el.style.display = 'block';
        }

        function hideApptError() {
            document.getElementById('a_bookingError').style.display = 'none';
        }

        async function apptSendOtp() {
            const name = document.getElementById('a_name').value.trim();
            const phone = document.getElementById('a_phone').value.trim();
            if (!name) {
                alert('Please enter your name first.');
                return;
            }
            if (!phone) {
                alert('Please enter your phone number.');
                return;
            }

            const btn = document.getElementById('a_sendOtpBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending…';

            try {
                const res = await fetch('{{ route('appointment.send-otp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': APPT_CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name,
                        phone
                    }),
                });
                const data = await res.json();
                if (data.success) {
                    document.getElementById('a_otpRow').style.display = 'block';
                    const msg = document.getElementById('a_otpMsg');
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

        async function apptVerifyOtp() {
            const phone = document.getElementById('a_phone').value.trim();
            const otp = document.getElementById('a_otp').value.trim();
            if (!otp || otp.length !== 6) {
                alert('Please enter the 6-digit OTP.');
                return;
            }

            const btn = event.target;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            const msg = document.getElementById('a_otpMsg');

            try {
                const res = await fetch('{{ route('appointment.verify-otp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': APPT_CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        phone,
                        otp
                    }),
                });
                const data = await res.json();
                if (data.success) {
                    apptOtpVerified = true;
                    document.getElementById('a_otpRow').style.display = 'none';
                    const badge = document.getElementById('a_verifiedBadge');
                    badge.style.display = 'flex';
                    msg.textContent = '';
                    document.getElementById('aStepDot2').classList.add('active');
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

        async function apptSubmit() {
            if (APPT_OTP_REQUIRED && !apptOtpVerified) {
                alert('Please verify your phone number first.');
                return;
            }

            const selected = document.getElementById('a_selection').value;

            let service_id = null;
            let appointment_type_id = null;

            if (!selected) {
                showApptError('Please select service or appointment type.');
                return;
            }

            // 🔥 पहचान करो क्या select हुआ है
            if (selected.startsWith('service_')) {
                service_id = selected.replace('service_', '');
            }

            if (selected.startsWith('type_')) {
                appointment_type_id = selected.replace('type_', '');
            }


            const payload = {
                name: document.getElementById('a_name').value.trim(),
                phone: document.getElementById('a_phone').value.trim(),
                email: document.getElementById('a_email').value.trim(),
                pet_type: document.getElementById('a_pet_type').value,
                pet_name: document.getElementById('a_pet_name').value.trim(),
                service_id: service_id,
                appointment_type_id: appointment_type_id,
                preferred_date: document.getElementById('a_preferred_date').value,
                city_id: document.getElementById('a_city_id').value,
                message: document.getElementById('a_message').value.trim(),
                g_recaptcha_response: (typeof grecaptcha !== 'undefined' && document.getElementById(
                        'a_recaptcha')) ?
                    grecaptcha.getResponse() : '',
            };

            if (!payload.name || !payload.phone || !payload.email || !payload.pet_type ||
                !payload.pet_name || !payload.preferred_date || !payload.city_id) {

                showApptError('Please fill in all required fields.');
                return;
            }

            // 🔥 separate check
            if (!payload.service_id && !payload.appointment_type_id) {
                showApptError('Please select service or appointment type.');
                return;
            }


            const btn = document.getElementById('a_submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing…';
            hideApptError();

            document.getElementById('aStepDot3').classList.add('active');

            try {
                const res = await fetch('{{ route('appointment.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': APPT_CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();

                if (!data.success) {
                    showApptError(data.message || 'Booking failed. Please try again.');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-calendar-check"></i> Book My Appointment';
                    document.getElementById('aStepDot3').classList.remove('active');
                    return;
                }

                if (data.requires_payment) {
                    apptInitRazorpay(data);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-credit-card"></i> Proceed to Payment';
                    return;
                }

                window.location.href = APPT_SHOW_BASE_URL + '/' + data.booking_id;

            } catch (e) {
                showApptError('Unable to submit booking. Please check your connection and try again.');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-calendar-check"></i> Book My Appointment';
                document.getElementById('aStepDot3').classList.remove('active');
            }
        }

        function apptInitRazorpay(data) {
            const options = {
                key: APPT_RAZORPAY_KEY,
                amount: data.amount,
                currency: data.currency,
                order_id: data.order_id,
                name: APPT_SITE_NAME,
                description: 'Appointment Booking Fee',
                prefill: {
                    name: data.customer_name,
                    email: data.customer_email,
                    contact: data.customer_phone,
                },
                theme: {
                    color: '#FF6B35'
                },
                handler: async function(response) {
                    try {
                        const vRes = await fetch('{{ route('appointment.verify-payment') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': APPT_CSRF,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                booking_id: data.booking_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_signature: response.razorpay_signature,
                            }),
                        });
                        const vData = await vRes.json();
                        if (vData.success) {
                            window.location.href = APPT_SHOW_BASE_URL + '/' + vData.booking_id;
                        } else {
                            showApptError(vData.message || 'Payment verification failed.');
                        }
                    } catch (e) {
                        showApptError('Payment verification failed. Please contact support.');
                    }
                },
                modal: {
                    ondismiss: function() {}
                },
            };
            new Razorpay(options).open();
        }
    </script>

@endsection
@endsection
