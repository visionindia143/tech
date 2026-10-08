@extends('layouts.frontend')

@section('meta_title', $page->meta_title ?? 'Contact Us - PawCare')
@section('meta_description', $page->meta_description ?? 'Get in touch with PawCare. We would love to hear from you.')

@section('content')
    <div class="page-hero">
        <div class="container">
            <h1>Contact Us</h1>
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a> / Contact
            </div>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="contact-grid">

                {{-- Form --}}
                <div class="fade-up">
                    <h2 style="font-family:var(--font-display);font-size:1.8rem;margin-bottom:8px;">Get in Touch</h2>
                    <p style="color:var(--clr-gray);margin-bottom:32px;">Have a question or need help? Send us a message and
                        we'll get back to you shortly.</p>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul style="list-style:none;margin:0;padding:0;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label>Your Name *</label>
                                <input type="text" name="name" class="form-control" required
                                    value="{{ old('name') }}" placeholder="John Doe">
                            </div>
                            <div class="form-group">
                                <label>Email Address *</label>
                                <input type="email" name="email" class="form-control" required
                                    value="{{ old('email') }}" placeholder="john@example.com">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}"
                                    placeholder="+91 9876543210">
                            </div>
                            <div class="form-group">
                                <label>Subject</label>
                                <input type="text" name="subject" class="form-control" value="{{ old('subject') }}"
                                    placeholder="How can we help?">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Message *</label>
                            <textarea name="message" class="form-control" required rows="5" placeholder="Tell us more...">{{ old('message') }}</textarea>
                        </div>
                        @if (\App\Services\RecaptchaService::isEnabled())
                            <div class="g-recaptcha" data-sitekey="{{ \App\Services\RecaptchaService::getSiteKey() }}"
                                style="margin-bottom:16px;"></div>
                            @if ($errors->has('g-recaptcha-response'))
                                <div style="color:#dc2626;font-size:0.875rem;margin-bottom:12px;">
                                    {{ $errors->first('g-recaptcha-response') }}</div>
                            @endif
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>

                {{-- Info Cards --}}
                <div class="fade-up">
                    <div style="display:flex;flex-direction:column;gap:20px;">
                        <div class="contact-info-card">
                            <div class="contact-info-icon" style="background:var(--clr-warm);color:var(--clr-primary);">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <h4>Email Us</h4>
                                <p>{{ $settings['site_email'] }}</p>
                            </div>
                        </div>
                        <div class="contact-info-card">
                            <div class="contact-info-icon" style="background:#ECFDF5;color:var(--clr-accent);">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <h4>Call Us</h4>
                                <p>{{ $settings['site_phone'] }}</p>
                            </div>
                        </div>
                        {{-- <div class="contact-info-card">
                            <div class="contact-info-icon" style="background:#EFF6FF;color:#3B82F6;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h4>Visit Us</h4>
                                <p>{{ $settings['site_address'] }}</p>
                            </div>
                        </div> --}}
                        @php $wa = $settings['whatsapp_number']; @endphp
                        @if ($wa)
                            @php
                                $msg = urlencode(
                                    'Hi Pretty Pet, I want to book a professional consultation for my pet.',
                                );
                            @endphp

                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $wa) }}?text={{ $msg }}"
                                target="_blank" class="contact-info-card"
                                style="text-decoration:none;border-color:#25D366;">

                                <div class="contact-info-icon" style="background:#ECFDF5;color:#25D366;">
                                    <i class="fab fa-whatsapp"></i>
                                </div>

                                <div>
                                    <h4 style="color:var(--clr-secondary);">WhatsApp</h4>
                                    <p style="color:#25D366;font-weight:600;">Chat with us</p>
                                </div>

                            </a>
                        @endif
                    </div>

                    @if ($page && $page->content)
                        <div
                            style="margin-top:28px;background:white;border-radius:var(--radius-lg);padding:28px;border:1px solid var(--clr-border);">
                            <div class="blog-content">{!! $page->content !!}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <style>
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: start;
        }

        .contact-info-card {
            background: white;
            border-radius: var(--radius-lg);
            padding: 22px 24px;
            border: 1px solid var(--clr-border);
            display: flex;
            gap: 18px;
            align-items: center;
            transition: var(--transition);
        }

        .contact-info-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .contact-info-icon {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.2rem;
        }

        .contact-info-card h4 {
            font-size: 0.95rem;
            margin-bottom: 3px;
            font-weight: 600;
        }

        .contact-info-card p {
            color: var(--clr-gray);
            font-size: 0.88rem;
        }

        @media (max-width: 900px) {
            .contact-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
        }

        @media (max-width: 480px) {
            .contact-info-card {
                padding: 16px;
                gap: 14px;
            }

            .contact-info-icon {
                width: 44px;
                height: 44px;
                font-size: 1rem;
            }
        }
    </style>
@endsection

@if (\App\Services\RecaptchaService::isEnabled())
    @section('scripts')
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endsection
@endif
