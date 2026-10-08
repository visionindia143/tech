@extends('layouts.frontend')

@section('meta_title', 'Book Diagnostic Test - ' . $settings['site_name'])
@section('meta_description',
    'Book a diagnostic test online. Choose from active tests, share your medical details, and
    get a quick booking confirmation.')

@section('styles')
    <style>
        .diag-hero {
            background: linear-gradient(135deg, #12263F 0%, #1F3C5C 50%, #0F172A 100%);
            color: #fff;
            padding: 86px 0 12px;
            position: relative;
            overflow: hidden;
        }

        .diag-hero::before,
        .diag-hero::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .diag-hero::before {
            width: 360px;
            height: 360px;
            right: -120px;
            top: -120px;
            background: radial-gradient(circle, rgba(255, 122, 89, 0.18) 0%, rgba(255, 122, 89, 0) 70%);
        }

        .diag-hero::after {
            width: 280px;
            height: 280px;
            left: -80px;
            bottom: -120px;
            background: radial-gradient(circle, rgba(6, 214, 160, 0.18) 0%, rgba(6, 214, 160, 0) 70%);
        }

        .diag-hero-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 32px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .diag-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .diag-hero h1 {
            font-family: var(--font-display);
            font-size: clamp(2.2rem, 4vw, 4rem);
            line-height: 1.05;
            margin-bottom: 16px;
            max-width: 14ch;
        }

        .diag-hero p {
            max-width: 620px;
            font-size: 1.03rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.78);
            margin-bottom: 26px;
        }



        .diag-page {
            background: linear-gradient(180deg, #F7FAFC 0%, #FFFFFF 100%);
            padding: 56px 0 84px;
        }

        .diag-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
            gap: 28px;
            align-items: start;
        }

        .diag-card,
        .diag-side {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);
            border: 1px solid rgba(15, 23, 42, 0.06);
            overflow: hidden;
        }

        .diag-card-head {
            padding: 26px 28px 22px;
            border-bottom: 1px solid var(--clr-border);
            background: linear-gradient(135deg, rgba(255, 122, 89, 0.06), rgba(6, 214, 160, 0.05));
        }

        .diag-card-head h2,
        .diag-side-head h3 {
            margin: 0;
            font-family: var(--font-display);
            color: var(--clr-secondary);
        }

        .diag-card-head p,
        .diag-side-head p {
            margin: 8px 0 0;
            color: var(--clr-gray);
            line-height: 1.6;
        }



        .diag-banner {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            background: #FFF7ED;
            border: 1px solid #FED7AA;
            color: #9A3412;
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 22px;
        }

        .diag-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .diag-field {
            margin-bottom: 18px;
        }

        .diag-field label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--clr-secondary);
            margin-bottom: 8px;
        }

        .diag-input,
        .diag-select,
        .diag-textarea {
            width: 100%;
            border: 1.5px solid #D7DEE8;
            border-radius: 14px;
            background: #FAFBFD;
            padding: 12px 14px;
            color: var(--clr-secondary);
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .diag-input:focus,
        .diag-select:focus,
        .diag-textarea:focus {
            outline: none;
            border-color: var(--clr-primary);
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.12);
            background: #fff;
        }

        .diag-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .diag-actions {
            display: flex;
            gap: 14px;
            align-items: center;
            margin-top: 10px;
        }

        .diag-submit {
            min-width: 200px;
            justify-content: center;
        }

        .diag-message {
            display: none;
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 14px;
            font-size: 0.92rem;
        }

        .diag-message.success {
            display: block;
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            color: #065F46;
        }

        .diag-message.error {
            display: block;
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #991B1B;
        }

        .diag-side-head {
            padding: 24px 24px 18px;
            border-bottom: 1px solid var(--clr-border);
        }

        .test-catalog {
            display: grid;
            gap: 14px;
        }

        .test-card {
            border: 1px solid var(--clr-border);
            border-radius: 18px;
            padding: 16px 18px;
            background: #fff;
        }

        .test-card-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .test-card h4 {
            margin: 0;
            color: var(--clr-secondary);
            font-size: 1rem;
        }

        .test-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 5px 10px;
            background: #EEF2FF;
            color: #4338CA;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .test-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 12px;
            font-size: 0.82rem;
            color: var(--clr-gray);
        }

        .test-meta span {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 999px;
            padding: 6px 10px;
        }

        .empty-state {
            padding: 18px;
            border: 1px dashed #CBD5E1;
            border-radius: 16px;
            color: var(--clr-gray);
            text-align: center;
            background: #F8FAFC;
        }

        @media (max-width: 992px) {

            .diag-hero-grid,
            .diag-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {



            .diag-form-grid,
            .diag-actions {
                grid-template-columns: 1fr;
                flex-direction: column;
                align-items: stretch;
            }

            .diag-submit {
                width: 100%;
            }
        }

        .diag-premium-form {
            background: #fff;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
        }

        /* STEP */
        .diag-steps {
            display: flex;
            align-items: center;
            margin-bottom: 28px;
        }

        .diag-step {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .diag-step-num {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #FFEDE7;
            color: #FF6B35;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .diag-step.active .diag-step-num {
            background: #FF6B35;
            color: #fff;
        }

        /* SECTION */
        .diag-section {
            margin-top: 25px;
        }

        .diag-section-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: black;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .diag-section-title::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }

        /* INPUT */
        .diag-field {
            margin-bottom: 18px;
        }

        .diag-input,
        .diag-select,
        .diag-textarea {
            width: 100%;
            border-radius: 12px;
            border: 1.5px solid #E5E7EB;
            padding: 12px 14px;
            background: #FAFAFA;
            transition: 0.2s;
        }

        .diag-input:focus,
        .diag-select:focus,
        .diag-textarea:focus {
            border-color: #FF6B35;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.12);
            background: #fff;
            outline: none;
        }

        .diag-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* BUTTON */
        .diag-submit {
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1rem;
        }

        /* OTP Overlay and Field Styles */
        .otp-group {
            display: none;
            margin-top: 10px;
        }

        .verify-btn {
            padding: 5px 15px;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .is-verified {
            border-color: #06D6A0 !important;
            background-color: #f0fff4 !important;
        }

        .is-invalid {
            border-color: #ef4444 !important;
        }

        .diag-premium-form.loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* MOBILE */
        @media(max-width:640px) {
            .diag-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')

    <div class="page-hero">
        <div class="diag-pill"><i class="fas fa-vials"></i> Diagnostic/Test Booking </div>
        <div class="container">
            <h1>Book diagnostic/tests</h1>

            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a> /Diagnostic-booking
            </div>
        </div>
    </div>

    <section class="diag-page">
        <div class="container">
            <div class="diag-grid">
                <div class="diag-card">
                    <div class="diag-card-head">
                        <h2>Request a Diagnostic Test</h2>
                        <p>Fill in your personal and medical details. The admin panel receives the booking immediately and
                            can confirm or update its status from the centralized booking dashboard.</p>
                    </div>
                    <div class="diag-card-body">
                        <div id="diagMessage" class="diag-message"></div>



                        <form id="diagnosticBookingForm" class="diag-premium-form" method="POST"
                            action="{{ route('diagnostic.booking.store') }}">
                            @csrf


                            <!-- USER -->
                            <div class="diag-section">
                                <div class="diag-section-title">
                                    <i class="fas fa-user"></i> Contact Info
                                </div>

                                <div class="diag-field">
                                    <input type="text" name="name" class="diag-input" id="user_name"
                                        placeholder="Full Name" required>
                                </div>

                                <div class="diag-row">
                                    <div class="diag-field">
                                        <div style="display: flex; gap: 5px;">
                                            <input type="tel" name="phone" id="user_phone" class="diag-input"
                                                placeholder="Phone Number" required>
                                            @if ($otpRequired)
                                                <button type="button" id="sendOtpBtn"
                                                    class="btn btn-sm btn-outline-primary" style="white-space:nowrap;">Get
                                                    OTP</button>
                                            @endif
                                        </div>

                                        @if ($otpRequired)
                                            <div class="otp-group" id="otpSection">
                                                <div style="display: flex; gap: 5px; margin-top: 8px;">
                                                    <input type="text" id="otp_code" class="diag-input"
                                                        placeholder="6-digit OTP" maxlength="6">
                                                    <button type="button" id="verifyOtpBtn"
                                                        class="btn btn-success btn-sm">Verify</button>
                                                </div>
                                                <small class="text-muted">Enter the code sent to your phone.</small>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="diag-field">
                                        <input type="email" name="email" class="diag-input" placeholder="Email Address"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <!-- PET -->
                            <div class="diag-section">
                                <div class="diag-section-title">
                                    <i class="fas fa-paw"></i> Pet Details
                                </div>

                                <div class="diag-row">
                                    <div class="diag-field">
                                        <select id="diag_pet_type" name="pet_type" class="diag-select" required>
                                            <option value="">Select Pet Type</option>
                                            @foreach ($petTypes as $petType)
                                                <option value="{{ $petType->name }}">
                                                    {{ $petType->icon }} {{ $petType->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="diag-field">
                                        <input type="text" name="pet_name" class="diag-input" placeholder="Pet Name"
                                            required>
                                    </div>
                                </div>

                                <div class="diag-row">
                                    <div class="diag-field">
                                        <select name="gender" class="diag-select" required>
                                            <option value="">Select Pet Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>

                                    <div class="diag-field">
                                        <input type="number" name="age" class="diag-input" min="0"
                                            max="120" placeholder="Pet Age (optional)">
                                    </div>
                                </div>
                            </div>

                            <!-- TEST -->
                            <div class="diag-section">
                                <div class="diag-section-title">
                                    <i class="fas fa-vials"></i> Test Details
                                </div>

                                <div class="diag-row">
                                    <div class="diag-field">
                                        <select id="diag_test" name="test_type_id" class="diag-select" required></select>
                                    </div>

                                    <div class="diag-field">
                                        <input type="date" name="preferred_date" class="diag-input" required>
                                    </div>
                                </div>

                                <div class="diag-field">
                                    <select name="city_id" class="diag-select" required>
                                        <option value="">Select City</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- NOTES -->
                            <div class="diag-section">
                                <div class="diag-section-title">
                                    <i class="fas fa-sticky-note"></i> Notes
                                </div>

                                <div class="diag-field">
                                    <textarea name="message" class="diag-textarea" placeholder="Additional info..."></textarea>
                                </div>
                            </div>

                            <!-- BUTTON -->
                            <button type="submit" id="diagSubmitBtn" class="btn btn-primary diag-submit">
                                <i class="fas fa-paper-plane"></i> Submit Booking
                            </button>

                        </form>
                    </div>

                    <div id="diagSuccess" style="display:none;padding:56px 28px;text-align:center;">
                        <div
                            style="width:92px;height:92px;margin:0 auto 22px;border-radius:50%;background:linear-gradient(135deg,#ECFDF5,#D1FAE5);display:flex;align-items:center;justify-content:center;color:#059669;font-size:2.4rem;box-shadow:0 0 0 12px rgba(5,150,105,0.08);">
                            <i class="fas fa-check"></i>
                        </div>
                        <h2 style="font-family:var(--font-display);color:var(--clr-secondary);margin-bottom:10px;">Booking
                            received</h2>
                        <p style="color:var(--clr-gray);max-width:420px;margin:0 auto 24px;line-height:1.7;">Your
                            diagnostic
                            booking has been submitted successfully. The team will review it and update the status from the
                            admin dashboard.</p>
                        <a href="{{ route('diagnostic.booking') }}" class="btn-primary"><i class="fas fa-plus"></i> Book
                            Another Test</a>
                    </div>
                </div>

                <aside class="diag-side">
                    <div class="diag-side-head">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                            <h3>Active Tests</h3>
                            <span id="diagTestCount"
                                style="display:inline-flex;align-items:center;justify-content:center;min-width:34px;height:34px;padding:0 10px;border-radius:999px;background:rgba(255,107,53,0.12);color:var(--clr-primary);font-weight:700;">
                                {{ $testTypes->count() }}
                            </span>
                        </div>
                        <p>These tests are refreshed from the backend. Hidden tests disappear from the list without any code
                            change.</p>
                    </div>
                    <div class="diag-side-body">
                        <div id="testCatalog" class="test-catalog"></div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form          = document.getElementById('diagnosticBookingForm');
    const sendOtpBtn    = document.getElementById('sendOtpBtn');
    const verifyOtpBtn  = document.getElementById('verifyOtpBtn');
    const submitBtn     = document.getElementById('diagSubmitBtn');
    const testSelect    = document.getElementById('diag_test');
    const petTypeSelect = document.getElementById('diag_pet_type');
    const testCatalog   = document.getElementById('testCatalog');
    const messageBox    = document.getElementById('diagMessage');
    const successBox    = document.getElementById('diagSuccess');
    const testCount     = document.getElementById('diagTestCount');
    const csrfToken     = '{{ csrf_token() }}';

    const OTP_REQUIRED    = @json($otpRequired);
    const RAZORPAY_KEY    = @json($razorpayKey);
    const testsEndpoint   = @json(route('diagnostic.booking.tests'));
    const petTypesEndpoint= @json(route('diagnostic.booking.pet-types'));
    const initialTests    = @json($testTypes->values());
    const initialPetTypes = @json($petTypes->values());

    let isPhoneVerified = !OTP_REQUIRED;

    // ── OTP: Send ────────────────────────────────────────────────────────────────
    if (sendOtpBtn) {
        sendOtpBtn.onclick = async () => {
            const phone = document.getElementById('user_phone').value.trim();
            const name  = document.getElementById('user_name').value.trim();
            if (!phone || !name) { alert('Enter name & phone first'); return; }

            sendOtpBtn.disabled = true;
            sendOtpBtn.textContent = 'Sending...';

            try {
                const res  = await fetch('{{ route('diagnostic.send-otp') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ phone, name })
                });
                const data = await res.json();

                if (data.success) {
                    document.getElementById('otpSection').style.display = 'block';
                    alert('OTP Sent ✅');
                } else {
                    alert(data.message || 'Failed to send OTP');
                    sendOtpBtn.disabled = false;
                }
            } catch (e) {
                alert('Network error');
                sendOtpBtn.disabled = false;
            }

            sendOtpBtn.textContent = 'Resend OTP';
        };
    }

    // ── OTP: Verify ──────────────────────────────────────────────────────────────
    if (verifyOtpBtn) {
        verifyOtpBtn.onclick = async () => {
            const phone = document.getElementById('user_phone').value.trim();
            const otp   = document.getElementById('otp_code').value.trim();
            if (!otp || otp.length !== 6) { alert('Enter valid 6-digit OTP'); return; }

            verifyOtpBtn.disabled = true;

            try {
                const res  = await fetch('{{ route('diagnostic.verify-otp') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ phone, otp })
                });
                const data = await res.json();

                if (data.success) {
                    isPhoneVerified = true;
                    document.getElementById('otpSection').style.display = 'none';
                    document.getElementById('user_phone').classList.add('is-verified');
                    document.getElementById('user_phone').readOnly = true;
                    alert('Phone Verified ✅');
                } else {
                    alert(data.message || 'Invalid OTP');
                }
            } catch (e) {
                alert('Verification failed');
            }

            verifyOtpBtn.disabled = false;
        };
    }

    // ── Form Submit ───────────────────────────────────────────────────────────────
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!isPhoneVerified) {
            alert('Please verify your phone via OTP first.');
            return;
        }

        clearMessage();
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';

        try {
            const res  = await fetch('{{ route('diagnostic.booking.store') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: new FormData(form)
            });
            const data = await res.json();

            if (!res.ok || !data.success) {
                showMessage('error', data.message || 'Booking failed. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Booking';
                return;
            }

            if (data.requires_payment) {
                const rzp = new Razorpay({
                    key: RAZORPAY_KEY,
                    amount: data.amount,
                    currency: 'INR',
                    name: @json($settings['site_name'] ?? 'PawZone'),
                    description: 'Diagnostic Test Booking',
                    order_id: data.order_id,
                    prefill: {
                        name: data.customer_name,
                        email: data.customer_email,
                        contact: data.customer_phone
                    },
                    handler: async function (response) {
                        try {
                            const vRes  = await fetch('{{ route('diagnostic.verify-payment') }}', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                                body: JSON.stringify({
                                    booking_id:          data.booking_id,
                                    razorpay_order_id:   response.razorpay_order_id,
                                    razorpay_payment_id: response.razorpay_payment_id,
                                    razorpay_signature:  response.razorpay_signature
                                })
                            });
                            const vData = await vRes.json();

                            if (vData.success) {
                                showSuccess();
                            } else {
                                alert(vData.message || 'Payment verification failed.');
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Booking';
                            }
                        } catch (err) {
                            alert('Payment verification error. Please contact support.');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Booking';
                        }
                    },
                    modal: {
                        ondismiss: function () {
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Booking';
                        }
                    }
                });
                rzp.open();
                return;
            }

            showSuccess();

        } catch (error) {
            showMessage('error', 'Could not submit your booking. Please try again.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Booking';
        }
    });

    // ── Helpers ───────────────────────────────────────────────────────────────────
    function showSuccess() {
        form.style.display = 'none';
        successBox.style.display = 'block';
    }

    function showMessage(type, text) {
        messageBox.className = 'diag-message ' + type;
        messageBox.innerHTML = text;
    }

    function clearMessage() {
        messageBox.className = 'diag-message';
        messageBox.innerHTML = '';
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // ── Catalog ───────────────────────────────────────────────────────────────────
    function renderTests(tests) {
        const currentValue = testSelect.value;
        testSelect.innerHTML = '<option value="">Choose diagnostic test</option>';

        if (!tests.length) {
            testCatalog.innerHTML = '<div class="empty-state">No active tests are available right now. Please check back later.</div>';
            if (testCount) testCount.textContent = '0';
            return;
        }

        testCatalog.innerHTML = tests.map(test => {
            const price    = test.price ? '₹' + Number(test.price).toLocaleString('en-IN') : 'Contact for price';
            const duration = test.duration_minutes ? test.duration_minutes + ' min' : 'Flexible timing';
            const category = test.category ? escapeHtml(test.category) : 'General';

            testSelect.insertAdjacentHTML('beforeend',
                '<option value="' + test.id + '"'
                + (String(currentValue) === String(test.id) ? ' selected' : '')
                + '>' + escapeHtml(test.name) + '</option>'
            );

            return `<article class="test-card">
                <div class="test-chip"><i class="fas fa-tag"></i> ${category}</div>
                <div class="test-card-top">
                    <div>
                        <h4>${escapeHtml(test.name)}</h4>
                        <div style="font-size:0.85rem;color:var(--clr-gray);margin-top:4px;">${escapeHtml(test.description || 'Admin-managed diagnostic screening test')}</div>
                    </div>
                    <strong style="color:var(--clr-primary);white-space:nowrap;">${price}</strong>
                </div>
                <div class="test-meta">
                    <span><i class="fas fa-clock"></i> ${duration}</span>
                    ${test.category ? '<span><i class="fas fa-layer-group"></i> ' + category + '</span>' : ''}
                </div>
            </article>`;
        }).join('');

        if (testCount) testCount.textContent = String(tests.length);

        if (currentValue && !tests.some(t => String(t.id) === String(currentValue))) {
            testSelect.value = '';
            showMessage('error', 'The selected test is no longer active. Please choose another one.');
        }
    }

    function renderPetTypes(petTypes) {
        const currentValue = petTypeSelect.value;
        petTypeSelect.innerHTML = '<option value="">Select Pet Type</option>';

        petTypes.forEach(pt => {
            petTypeSelect.insertAdjacentHTML('beforeend',
                '<option value="' + escapeHtml(pt.name) + '"'
                + (String(currentValue) === String(pt.name) ? ' selected' : '')
                + '>' + escapeHtml((pt.icon ? pt.icon + ' ' : '') + pt.name) + '</option>'
            );
        });

        if (currentValue && !petTypes.some(pt => String(pt.name) === String(currentValue))) {
            petTypeSelect.value = '';
            showMessage('error', 'The selected pet type is no longer active. Please choose another one.');
        }
    }

    async function refreshTests() {
        try {
            const [tr, pr] = await Promise.all([
                fetch(testsEndpoint,    { headers: { 'Accept': 'application/json' } }),
                fetch(petTypesEndpoint, { headers: { 'Accept': 'application/json' } })
            ]);
            const tp = await tr.json();
            const pp = await pr.json();
            if (tp.success) renderTests(tp.tests || []);
            if (pp.success) renderPetTypes(pp.pet_types || []);
        } catch {
            if (!testCatalog.innerHTML.trim()) renderTests(initialTests);
            if (!petTypeSelect.querySelector('option[value]:not([value=""])')) renderPetTypes(initialPetTypes);
        }
    }

    renderTests(initialTests);
    renderPetTypes(initialPetTypes);
    refreshTests();
    setInterval(refreshTests, 30000);
});
</script>
@endsection
