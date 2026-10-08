@extends('layouts.frontend')

@section('meta_title', $pet->name . ' - Adopt Me - ' . $settings['site_name'])
@section('meta_description', $pet->short_description ?? 'Adopt ' . $pet->name . ', a lovely ' . $pet->pet_type . ' looking for a forever home.')

@section('content')
<div class="page-hero">
    <div class="container">
        <h1>{{ $pet->name }}</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> /
            <a href="{{ route('adoption.index') }}">Adoption</a> /
            {{ $pet->name }}
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1.1fr 0.9fr;gap:60px;align-items:start;">

            {{-- Pet Images --}}
            <div class="fade-up">
                @php $images = $pet->image_urls; @endphp
                @if(count($images))
                <div id="mainImg" style="border-radius:var(--radius-lg);overflow:hidden;margin-bottom:12px;height:420px;">
                    <img id="mainImgSrc" src="{{ $images[0] }}" alt="{{ $pet->name }}" style="width:100%;height:100%;object-fit:cover;">
                </div>
                @if(count($images) > 1)
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    @foreach($images as $i => $url)
                    <div onclick="document.getElementById('mainImgSrc').src='{{ $url }}'"
                         style="width:80px;height:80px;border-radius:var(--radius-sm);overflow:hidden;cursor:pointer;border:2px solid {{ $i===0?'var(--clr-primary)':'var(--clr-border)' }};transition:var(--transition);">
                        <img src="{{ $url }}" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    @endforeach
                </div>
                @endif
                @else
                <div style="height:420px;background:linear-gradient(135deg,var(--clr-warm),var(--clr-primary));border-radius:var(--radius-lg);display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-paw" style="font-size:8rem;color:white;opacity:.3;"></i>
                </div>
                @endif
            </div>

            {{-- Pet Details + Booking --}}
            <div class="fade-up">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                    <span style="background:var(--clr-warm);color:var(--clr-primary);padding:4px 14px;border-radius:50px;font-size:0.82rem;font-weight:700;text-transform:uppercase;">{{ $pet->pet_type }}</span>
                    @if($pet->status === 'available')
                        <span style="background:#ECFDF5;color:#065F46;padding:4px 14px;border-radius:50px;font-size:0.82rem;font-weight:700;">Available</span>
                    @elseif($pet->status === 'reserved')
                        <span style="background:#FFFBEB;color:#92400E;padding:4px 14px;border-radius:50px;font-size:0.82rem;font-weight:700;">Reserved</span>
                    @else
                        <span style="background:#F3F4F6;color:#374151;padding:4px 14px;border-radius:50px;font-size:0.82rem;font-weight:700;">Adopted</span>
                    @endif
                </div>

                <h1 style="font-family:var(--font-display);font-size:2.4rem;margin-bottom:12px;">{{ $pet->name }}</h1>

                @if($pet->short_description)
                    <p style="color:var(--clr-gray);font-size:1rem;margin-bottom:24px;">{{ $pet->short_description }}</p>
                @endif

                {{-- Traits --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:28px;">
                    @if($pet->breed)
                    <div style="background:var(--clr-light-gray);padding:12px 16px;border-radius:var(--radius-sm);">
                        <div style="font-size:0.75rem;color:var(--clr-gray);margin-bottom:2px;">Breed</div>
                        <div style="font-weight:600;">{{ $pet->breed }}</div>
                    </div>
                    @endif
                    @if($pet->age)
                    <div style="background:var(--clr-light-gray);padding:12px 16px;border-radius:var(--radius-sm);">
                        <div style="font-size:0.75rem;color:var(--clr-gray);margin-bottom:2px;">Age</div>
                        <div style="font-weight:600;">{{ $pet->age }}</div>
                    </div>
                    @endif
                    @if($pet->gender !== 'unknown')
                    <div style="background:var(--clr-light-gray);padding:12px 16px;border-radius:var(--radius-sm);">
                        <div style="font-size:0.75rem;color:var(--clr-gray);margin-bottom:2px;">Gender</div>
                        <div style="font-weight:600;">{{ ucfirst($pet->gender) }}</div>
                    </div>
                    @endif
                    @if($pet->size)
                    <div style="background:var(--clr-light-gray);padding:12px 16px;border-radius:var(--radius-sm);">
                        <div style="font-size:0.75rem;color:var(--clr-gray);margin-bottom:2px;">Size</div>
                        <div style="font-weight:600;">{{ ucfirst($pet->size) }}</div>
                    </div>
                    @endif
                    @if($pet->color)
                    <div style="background:var(--clr-light-gray);padding:12px 16px;border-radius:var(--radius-sm);">
                        <div style="font-size:0.75rem;color:var(--clr-gray);margin-bottom:2px;">Color</div>
                        <div style="font-weight:600;">{{ $pet->color }}</div>
                    </div>
                    @endif
                </div>

                {{-- Health Tags --}}
                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px;">
                    @if($pet->is_vaccinated)
                        <span style="background:#ECFDF5;color:#065F46;padding:6px 14px;border-radius:50px;font-size:0.82rem;font-weight:600;"><i class="fas fa-syringe"></i> Vaccinated</span>
                    @endif
                    @if($pet->is_neutered)
                        <span style="background:#EFF6FF;color:#1E40AF;padding:6px 14px;border-radius:50px;font-size:0.82rem;font-weight:600;"><i class="fas fa-check-circle"></i> Neutered</span>
                    @endif
                </div>

                {{-- Adoption Fee --}}
                @if($pet->adoption_fee)
                    <div style="background:var(--clr-warm);border-radius:var(--radius-md);padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;">
                        <span style="color:var(--clr-gray);">Adoption Fee</span>
                        <span style="font-size:1.8rem;font-weight:800;color:var(--clr-primary);">₹{{ number_format($pet->adoption_fee) }}</span>
                    </div>
                @else
                    <div style="background:#ECFDF5;border-radius:var(--radius-md);padding:14px 20px;margin-bottom:24px;">
                        <span style="color:#065F46;font-weight:700;font-size:1rem;"><i class="fas fa-gift"></i> Free Adoption</span>
                    </div>
                @endif

                @if($pet->status === 'available')
                    <button id="bookBtn" class="btn btn-primary" style="width:100%;padding:14px;font-size:1rem;" onclick="openBookingForm()">
                        <i class="fas fa-heart"></i> Adopt {{ $pet->name }}
                    </button>
                @else
                    <div style="text-align:center;padding:16px;background:var(--clr-light-gray);border-radius:var(--radius-md);color:var(--clr-gray);">
                        <i class="fas fa-info-circle"></i> This pet is currently {{ $pet->status }}.
                    </div>
                @endif
            </div>
        </div>

        {{-- Full Description --}}
        @if($pet->description)
        <div class="fade-up" style="margin-top:60px;">
            <div class="panel" style="padding:40px;">
                <h3 style="font-family:var(--font-display);font-size:1.6rem;margin-bottom:20px;">About {{ $pet->name }}</h3>
                <div style="color:var(--clr-gray);line-height:1.9;">{!! nl2br(e($pet->description)) !!}</div>
            </div>
        </div>
        @endif
    </div>
</section>

{{-- BOOKING MODAL --}}
@if($pet->status === 'available')
<div id="bookingModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;overflow-y:auto;padding:20px;">
    <div style="max-width:560px;margin:40px auto;background:white;border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-lg);">
        <div style="background:var(--clr-primary);padding:24px 28px;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h3 style="color:white;font-size:1.3rem;margin-bottom:4px;">Adopt {{ $pet->name }}</h3>
                <p style="color:rgba(255,255,255,0.8);font-size:0.85rem;">Fill in your details to start the adoption process</p>
            </div>
            <button onclick="closeBookingForm()" style="background:rgba(255,255,255,0.2);border:none;color:white;width:36px;height:36px;border-radius:50%;cursor:pointer;font-size:1.1rem;display:flex;align-items:center;justify-content:center;">&times;</button>
        </div>

        <div style="padding:28px;">
            {{-- Step 1: Contact Info --}}
            <div id="step1">
                @if($otpRequired)
                    <p style="color:var(--clr-gray);margin-bottom:24px;font-size:0.9rem;"><i class="fas fa-shield-alt" style="color:var(--clr-primary);"></i> We verify your phone number via WhatsApp OTP before confirming your booking.</p>
                @else
                    <p style="color:var(--clr-gray);margin-bottom:24px;font-size:0.9rem;"><i class="fas fa-info-circle" style="color:var(--clr-primary);"></i> Please provide your contact details for the adoption booking.</p>
                @endif

                {{-- Payment fee banner (shown when payment is enabled and pet has a fee > 0) --}}
                @if($paymentEnabled && $pet->adoption_fee > 0)
                    <div style="background:var(--clr-warm);border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;">
                        <span style="font-size:0.88rem;color:var(--clr-gray);"><i class="fas fa-credit-card" style="color:var(--clr-primary);"></i> Adoption Fee (Razorpay)</span>
                        <span style="font-weight:800;font-size:1.1rem;color:var(--clr-primary);">₹{{ number_format($pet->adoption_fee) }}</span>
                    </div>
                @endif

                <div class="bform-group">
                    <label>Your Name *</label>
                    <input type="text" id="b_name" class="bform-control" placeholder="John Doe" required>
                </div>
                <div class="bform-group">
                    <label>Phone Number * @if($otpRequired)<small style="color:var(--clr-gray);">(WhatsApp)</small>@endif</label>
                    @if($otpRequired)
                        <div style="display:flex;gap:10px;">
                            <input type="tel" id="b_phone" class="bform-control" placeholder="9876543210" style="flex:1;" required>
                            <button id="sendOtpBtn" onclick="sendOtp()" class="btn btn-primary" style="padding:10px 16px;white-space:nowrap;font-size:0.85rem;">
                                Send OTP
                            </button>
                        </div>
                    @else
                        <input type="tel" id="b_phone" class="bform-control" placeholder="9876543210" required>
                    @endif
                </div>
                @if($otpRequired)
                    <div id="otpRow" style="display:none;" class="bform-group">
                        <label>Enter OTP *</label>
                        <div style="display:flex;gap:10px;">
                            <input type="text" id="b_otp" class="bform-control" placeholder="6-digit OTP" maxlength="6" style="flex:1;letter-spacing:6px;font-size:1.2rem;text-align:center;">
                            <button onclick="verifyOtp()" class="btn btn-outline" style="padding:10px 16px;font-size:0.85rem;">Verify</button>
                        </div>
                        <small id="otpMsg" style="margin-top:6px;display:block;"></small>
                    </div>
                @endif
                <div id="verifiedBadge" style="display:none;background:#ECFDF5;padding:10px 16px;border-radius:var(--radius-sm);margin-bottom:16px;">
                    <i class="fas fa-check-circle" style="color:#065F46;"></i> <span style="color:#065F46;font-weight:600;">Phone verified!</span>
                </div>

                <div class="bform-group">
                    <label>Email Address *</label>
                    <input type="email" id="b_email" class="bform-control" placeholder="you@example.com" required>
                </div>
                <div class="bform-group">
                    <label>City</label>
                    <input type="text" id="b_city" class="bform-control" placeholder="Your city">
                </div>
                <div class="bform-group">
                    <label>Address</label>
                    <textarea id="b_address" class="bform-control" rows="2" placeholder="Your home address"></textarea>
                </div>
                <div class="bform-group">
                    <label>Additional Notes</label>
                    <textarea id="b_notes" class="bform-control" rows="2" placeholder="Why do you want to adopt this pet?"></textarea>
                </div>

                @if(\App\Services\RecaptchaService::isEnabled())
                <div id="b_recaptcha" class="g-recaptcha" data-sitekey="{{ \App\Services\RecaptchaService::getSiteKey() }}" style="margin-bottom:16px;"></div>
                @endif

                <div id="bookingError" style="display:none;background:#FEF2F2;color:#991B1B;padding:12px 16px;border-radius:var(--radius-sm);margin-bottom:16px;font-size:0.88rem;"></div>

                <button id="submitBtn" onclick="submitBooking()" class="btn btn-primary" style="width:100%;padding:14px;font-size:1rem;">
                    @if($paymentEnabled && $pet->adoption_fee)
                        <i class="fas fa-credit-card"></i> Proceed to Payment (₹{{ number_format($pet->adoption_fee) }})
                    @else
                        <i class="fas fa-heart"></i> Confirm Booking
                    @endif
                </button>
            </div>

            {{-- Step 2: Success --}}
            <div id="step3" style="display:none;text-align:center;padding:20px 0;">
                <div style="font-size:4rem;margin-bottom:16px;">🎉</div>
                <h3 style="font-size:1.4rem;margin-bottom:10px;color:var(--clr-secondary);">Booking Confirmed!</h3>
                <p style="color:var(--clr-gray);">Thank you! We'll contact you shortly via WhatsApp/phone to complete the adoption.</p>
                <button onclick="closeBookingForm()" class="btn btn-primary" style="margin-top:24px;">Close</button>
            </div>
        </div>
    </div>
</div>
@endif

@if($paymentEnabled)
{{-- Load Razorpay SDK whenever payment is enabled so initRazorpay() is always available --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif

<style>
.bform-group { margin-bottom:18px; }
.bform-group label { display:block;font-weight:600;font-size:0.85rem;margin-bottom:6px;color:var(--clr-secondary); }
.bform-control { width:100%;padding:10px 14px;border:1px solid var(--clr-border);border-radius:var(--radius-sm);font-family:var(--font-body);font-size:0.9rem;transition:border 0.2s; }
.bform-control:focus { outline:none;border-color:var(--clr-primary);box-shadow:0 0 0 3px rgba(255,107,53,0.1); }
</style>

@section('scripts')
@if(\App\Services\RecaptchaService::isEnabled())
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif
<script>
const CSRF         = '{{ csrf_token() }}';
const PET_ID       = {{ $pet->id }};
const OTP_REQUIRED = {{ $otpRequired ? 'true' : 'false' }};
const RAZORPAY_KEY = '{{ $razorpayKey }}';
const SITE_NAME    = '{{ $settings['site_name'] }}';
let otpVerified = false;

function openBookingForm() {
    document.getElementById('bookingModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function closeBookingForm() {
    document.getElementById('bookingModal').style.display = 'none';
    document.body.style.overflow = '';
}

async function sendOtp() {
    const name  = document.getElementById('b_name').value.trim();
    const phone = document.getElementById('b_phone').value.trim();
    if (!name)  { alert('Please enter your name first.'); return; }
    if (!phone) { alert('Please enter your phone number.'); return; }

    const btn = document.getElementById('sendOtpBtn');
    btn.disabled = true;
    btn.textContent = 'Sending...';

    try {
        const res  = await fetch('{{ route("adoption.send-otp") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ name, phone }),
        });
        const data = await res.json();

        if (data.success) {
            document.getElementById('otpRow').style.display = 'block';
            document.getElementById('otpMsg').textContent   = data.message;
            document.getElementById('otpMsg').style.color   = 'var(--clr-accent)';
        } else {
            alert(data.message || 'Failed to send OTP.');
        }
    } catch (e) {
        alert('Network error. Please try again.');
    }

    btn.disabled = false;
    btn.textContent = 'Send OTP';
}

async function verifyOtp() {
    const phone = document.getElementById('b_phone').value.trim();
    const otp   = document.getElementById('b_otp').value.trim();
    if (!otp || otp.length !== 6) { alert('Please enter the 6-digit OTP.'); return; }

    const btn = event.target;
    btn.disabled = true;
    btn.textContent = 'Verifying...';

    const msg = document.getElementById('otpMsg');

    try {
        const res  = await fetch('{{ route("adoption.verify-otp") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ phone, otp }),
        });
        const data = await res.json();

        if (data.success) {
            otpVerified = true;
            document.getElementById('otpRow').style.display    = 'none';
            document.getElementById('verifiedBadge').style.display = 'block';
            msg.textContent = '';
        } else {
            msg.textContent = data.message || 'Invalid OTP.';
            msg.style.color = '#EF4444';
        }
    } catch (e) {
        msg.textContent = 'Network error. Please try again.';
        msg.style.color = '#EF4444';
    }

    btn.disabled = false;
    btn.textContent = 'Verify';
}

async function submitBooking() {
    if (OTP_REQUIRED && !otpVerified) { alert('Please verify your phone number first.'); return; }

    const payload = {
        pet_adoption_id: PET_ID,
        name:    document.getElementById('b_name').value.trim(),
        phone:   document.getElementById('b_phone').value.trim(),
        email:   document.getElementById('b_email').value.trim(),
        city:    document.getElementById('b_city').value.trim(),
        address: document.getElementById('b_address').value.trim(),
        notes:   document.getElementById('b_notes').value.trim(),
        g_recaptcha_response: (typeof grecaptcha !== 'undefined' && document.getElementById('b_recaptcha'))
            ? grecaptcha.getResponse()
            : '',
    };

    if (!payload.name || !payload.phone || !payload.email) {
        document.getElementById('bookingError').style.display = 'block';
        document.getElementById('bookingError').textContent   = 'Please fill in all required fields.';
        return;
    }

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing…';
    document.getElementById('bookingError').style.display = 'none';

    try {
        const res  = await fetch('{{ route("adoption.book") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify(payload),
        });
        const data = await res.json();

        if (!data.success) {
            document.getElementById('bookingError').style.display = 'block';
            document.getElementById('bookingError').textContent   = data.message || 'Booking failed.';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-heart"></i> Confirm Booking';
            return;
        }

        if (data.requires_payment) {
            initRazorpay(data);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-heart"></i> Proceed to Payment';
            return;
        }

        // Success without payment
        document.getElementById('step1').style.display = 'none';
        document.getElementById('step3').style.display = 'block';

    } catch (e) {
        console.error('Booking submission error:', e);
        document.getElementById('bookingError').style.display = 'block';
        document.getElementById('bookingError').textContent   = 'Unable to submit booking. Please check your internet connection and try again. If the problem persists, reload the page or contact support.';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-heart"></i> Confirm Booking';
    }
}

@if($paymentEnabled)
{{-- initRazorpay is always defined when payment is enabled, regardless of per-pet fee --}}
function initRazorpay(data) {
    const options = {
        key:         RAZORPAY_KEY,
        amount:      data.amount,
        currency:    data.currency,
        order_id:    data.order_id,
        name:        SITE_NAME,
        description: 'Adoption Fee for ' + data.pet_name,
        prefill: {
            name:    data.customer_name,
            email:   data.customer_email,
            contact: data.customer_phone,
        },
        theme: { color: '#FF6B35' },
        handler: async function(response) {
            try {
                const vRes  = await fetch('{{ route("adoption.verify-payment") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    body: JSON.stringify({
                        booking_id:           data.booking_id,
                        razorpay_order_id:    response.razorpay_order_id,
                        razorpay_payment_id:  response.razorpay_payment_id,
                        razorpay_signature:   response.razorpay_signature,
                    }),
                });
                const vData = await vRes.json();
                if (vData.success) {
                    document.getElementById('step1').style.display = 'none';
                    document.getElementById('step3').style.display = 'block';
                } else {
                    document.getElementById('bookingError').style.display = 'block';
                    document.getElementById('bookingError').textContent   = vData.message || 'Payment verification failed.';
                }
            } catch (e) {
                console.error('Payment verification error:', e);
                document.getElementById('bookingError').style.display = 'block';
                document.getElementById('bookingError').textContent   = 'Payment verification failed. Please contact support if the issue persists.';
            }
        },
        modal: { ondismiss: function() {} },
    };
    const rzp = new Razorpay(options);
    rzp.open();
}
@endif
</script>
@endsection
