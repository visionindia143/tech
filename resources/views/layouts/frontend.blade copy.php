<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- CRITICAL: Inline styles — load before any external CSS --}}
    <style>
        html,
        body {
            background: #FFFFFF;
        }

        body {
            visibility: hidden;
        }

        body.page-ready {
            visibility: visible;
        }

        /* Preloader ko visibility:hidden body ke bawajood dikhao */
        #preloader {
            visibility: visible;
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: #1A1B2F;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    {{-- Dynamic SEO Meta Tags --}}
    <title>@yield('meta_title', $settings['default_meta_title'])</title>
    <meta name="description" content="@yield('meta_description', $settings['default_meta_description'])">
    <meta name="keywords" content="@yield('meta_keywords', $settings['default_meta_keywords'])">

    {{-- Canonical URL --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/minslider.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/allSlider.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/floating-icon.css') }}">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('meta_title', $settings['default_meta_title'])">
    <meta property="og:description" content="@yield('meta_description', $settings['default_meta_description'])">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $settings['site_name'] }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('meta_title', 'PawCare')">
    <meta name="twitter:description" content="@yield('meta_description', '')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-default.jpg'))">



<link rel="icon" type="image/png"
      href="{{ asset('uploads/settings/' . $settings['site_logo']) }}?v={{ time() }}">

<link rel="shortcut icon"
      href="{{ asset('uploads/settings/' . $settings['site_logo']) }}?v={{ time() }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,500;0,700;1,500&display=swap"
        rel="stylesheet">

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --clr-primary: {{ $settings['theme_primary'] }};
            --clr-primary-dark: {{ $settings['theme_primary_dark'] }};
            --clr-secondary: {{ $settings['theme_secondary'] }};
            --clr-accent: {{ $settings['theme_accent'] }};
            --clr-button-text: {{ $settings['theme_button_text'] }};
            --clr-warm: #FFF3E0;
            --clr-cream: #FFFAF5;
            --clr-dark: #1A1B2F;
            --clr-gray: #6B7280;
            --clr-light-gray: #F3F4F6;
            --clr-white: #FFFFFF;
            --clr-border: #E5E7EB;
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 22px;
            --radius-xl: 32px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 20px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 12px 40px rgba(0, 0, 0, 0.12);
            --shadow-glow: 0 0 40px rgba(255, 107, 53, 0.15);
            --font-body: 'Outfit', sans-serif;
            --font-display: 'Playfair Display', serif;
            --transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ── PRELOADER — inline so it applies before any external CSS loads ── */
        /*
         * body is hidden by default so NO page content ever flashes.
         * Only #preloader overrides this with visibility:visible.
         * JS reveals body after window.load.
         */
        /*
         * visibility:hidden hides body content but children can override it
         * with visibility:visible — unlike opacity:0 which cannot be overridden.
         */


        #preloader {
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: #1A1B2F;
            display: flex;
            align-items: center;
            justify-content: center;
            visibility: visible;
            /* overrides body visibility:hidden */
            opacity: 1;
            transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1),
                visibility 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #preloader.preloader-hide {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .preloader-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .preloader-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, {{ $settings['theme_primary'] }}, {{ $settings['theme_primary_dark'] }});
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #fff;
            animation: plPulse 1.4s ease-in-out infinite;
        }

        @keyframes plPulse {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 107, 53, .5);
            }

            50% {
                transform: scale(1.09);
                box-shadow: 0 0 0 20px rgba(255, 107, 53, 0);
            }
        }

        .preloader-brand-name {
            font-family: 'Outfit', system-ui, sans-serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 3px;
            text-transform: uppercase;
            opacity: 0;
            animation: plFadeUp .6s .25s ease forwards;
        }

        @keyframes plFadeUp {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .preloader-paws {
            display: flex;
            align-items: center;
            gap: 8px;
            height: 26px;
        }

        .paw-step {
            font-size: .95rem;
            color: {{ $settings['theme_primary'] }};
            opacity: 0;
            animation: plPawWalk 1.8s ease-in-out infinite;
        }

        .paw-step:nth-child(1) {
            animation-delay: 0s;
            transform: rotate(-25deg) translateY(2px);
        }

        .paw-step:nth-child(2) {
            animation-delay: .22s;
            transform: rotate(25deg) translateY(-3px);
        }

        .paw-step:nth-child(3) {
            animation-delay: .44s;
            transform: rotate(-25deg) translateY(2px);
        }

        .paw-step:nth-child(4) {
            animation-delay: .66s;
            transform: rotate(25deg) translateY(-3px);
        }

        .paw-step:nth-child(5) {
            animation-delay: .88s;
            transform: rotate(-25deg) translateY(2px);
        }

        @keyframes plPawWalk {
            0% {
                opacity: 0;
                transform: scale(.4);
            }

            25% {
                opacity: 1;
                transform: scale(1);
            }

            70% {
                opacity: .4;
            }

            100% {
                opacity: 0;
            }
        }




        /* ───────────────────────────────────────────────────────── */
    </style>
    @yield('styles')
</head>

<body>

    {{-- PRELOADER --}}
    <div id="preloader">
        <div class="preloader-inner">
            <div class="preloader-logo">
                <i class="fas fa-paw"></i>
            </div>
            <p class="preloader-brand-name">{{ $settings['site_name'] }}</p>
            <div class="preloader-paws">
                <i class="fas fa-paw paw-step"></i>
                <i class="fas fa-paw paw-step"></i>
                <i class="fas fa-paw paw-step"></i>
                <i class="fas fa-paw paw-step"></i>
                <i class="fas fa-paw paw-step"></i>
            </div>
        </div>
    </div>




    <!-- Floating Social Media Bar -->
    <div class="float-sm">
        <div class="fl-fl float-wtsp">
            <i class="fab fa-whatsapp"></i>
            <a
                href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) }}?text={{ urlencode('Hi, I want to book a home visit veterinary service for my pet.') }}">Chat
                With Us</a>
        </div>
        <div class="fl-fl float-ph">
            <i class="fa fa-phone"></i>
            <a href="tel:{{ preg_replace('/[^0-9]/', '', $settings['site_phone']) }}">Call Us</a>
        </div>
    </div>

    {{-- NAVBAR --}}
    <nav class="navbar" id="navbar">
        <div class="container">
            <div class="nav-inner">
                <a href="{{ route('home') }}" class="nav-logo">
                    @if ($settings['site_logo'])
                        <img src="{{ asset('uploads/settings/' . $settings['site_logo']) }}"
                            alt="{{ $settings['site_name'] }}">
                    @else
                        <span class="paw-icon"><i class="fas fa-paw"></i></span>
                    @endif
                    <span>{{ $settings['site_name'] }}</span>
                </a>

                <button class="mobile-toggle" onclick="document.querySelector('.nav-links').classList.toggle('open')">
                    <i class="fas fa-bars"></i>
                </button>

                <ul class="nav-links">
                    <li><a href="{{ route('home') }}"
                            class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('contact') }}"
                            class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                    <li><a href="{{ route('blogs') }}"
                            class="{{ request()->routeIs('blogs') || request()->routeIs('blog.detail') ? 'active' : '' }}">Blog</a>
                    </li>
                    <li><a href="{{ route('adoption.index') }}"
                            class="{{ request()->routeIs('adoption.*') ? 'active' : '' }}">Adopt a Pet</a></li>
                    <li><a href="{{ route('about') }}"
                            class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>

                    <li><a href="{{ route('pet.nutrition') }}"
                            class="{{ request()->routeIs('pet.nutrition') ? 'active' : '' }}">Pet Nutrition</a></li>
                    <li><a href="{{ route('appointment') }}" class="nav-cta">Book Appointment</a></li>
                    {{-- <li><a href="{{ route('diagnostic.booking') }}" class="nav-cta" style="margin-left:10px;">Book
                            Diagnostic Test</a></li> --}}
                </ul>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">

                    <!-- Logo -->
                    <img src="{{ asset('assets/frontend/img/Pretty-pet-white-logo.png') }}"
                        alt="{{ $settings['site_name'] }}">

                    <!-- Site Name -->
                    <h3>{{ $settings['site_name'] }}</h3>

                    <!-- Tagline -->
                    <p>{{ $settings['site_tagline'] }}</p>

                    <!-- Social Icons -->
                    <div class="footer-social">
                        @if ($settings['facebook_url'])
                            <a href="{{ $settings['facebook_url'] }}" target="_blank" rel="noopener">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif

                        @if ($settings['instagram_url'])
                            <a href="{{ $settings['instagram_url'] }}" target="_blank" rel="noopener">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif

                        @if ($settings['twitter_url'])
                            <a href="{{ $settings['twitter_url'] }}" target="_blank" rel="noopener">
                                <i class="fab fa-twitter"></i>
                            </a>
                        @endif

                        @if ($settings['youtube_url'])
                            <a href="{{ $settings['youtube_url'] }}" target="_blank" rel="noopener">
                                <i class="fab fa-youtube"></i>
                            </a>
                        @endif
                    </div>

                </div>
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('blogs') }}">Blog</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Services</h4>
                    <ul>
                        <li><a href="{{ route('appointment') }}">Book Appointment </a></li>
                        <li><a href="{{ route('vaccination') }}">Book Vaccination/Deworming</a></li>
                        <li><a href="{{ route('diagnostic.booking') }}">Book Diagnostic & Test</a></li>
                        <li><a href="{{ route('pet.nutrition') }}">Pet Nutrition</a></li>
                        <li><a href="/services">All Services</a></li>

                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Contact Info</h4>
                    <ul>
                        @if ($settings['site_email'])
                            <li><i class="fas fa-envelope" style="color:var(--clr-primary);margin-right:8px"></i>
                                {{ $settings['site_email'] }}</li>
                        @endif
                        @if ($settings['site_phone'])
                            <li><i class="fas fa-phone" style="color:var(--clr-primary);margin-right:8px"></i>
                                {{ $settings['site_phone'] }}</li>
                        @endif
                        {{-- @if ($settings['site_address'])
                            <li><i class="fas fa-map-marker-alt"
                                    style="color:var(--clr-primary);margin-right:8px"></i>
                                {{ $settings['site_address'] }}</li>
                        @endif --}}
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ $settings['site_name'] }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        /* ── PRELOADER ───────────────────────────────────────────── */
        /* ── PRELOADER ───────────────────────────────────────────── */
        (function() {
            var loader = document.getElementById('preloader');
            if (!loader) return;

            var startAt = Date.now();
            var MIN_MS = 500; // minimum display (thoda kam kiya)
            var MAX_MS = 3000; // maximum wait — iske baad force dismiss
            var dismissed = false;

            function hideLoader(targetLoader) {
                if (!targetLoader) return;
                // Clear inline overrides so CSS class can hide reliably.
                targetLoader.style.opacity = '';
                targetLoader.style.visibility = '';
                targetLoader.style.transition = '';
                targetLoader.classList.add('preloader-hide');
            }

            function showLoader(targetLoader) {
                if (!targetLoader) return;
                targetLoader.classList.remove('preloader-hide');
            }

            function dismiss() {
                if (dismissed) return;
                dismissed = true;

                var waited = Date.now() - startAt;
                var remaining = Math.max(0, MIN_MS - waited);

                setTimeout(function() {
                    // 1) body ko pehle visible karo (behind preloader)
                    document.body.classList.add('page-ready');

                    // 2) preloader fade out
                    hideLoader(loader);
                }, remaining);
            }

            function ensurePageVisible() {
                // Back/forward cache restore pe body hidden state me na rahe.
                document.body.classList.add('page-ready');
                var navOverlay = document.getElementById('nav-preloader-overlay');
                if (navOverlay && navOverlay.parentNode) {
                    navOverlay.parentNode.removeChild(navOverlay);
                }
                var existingLoader = document.getElementById('preloader');
                if (existingLoader) {
                    hideLoader(existingLoader);
                }
            }

            // DOMContentLoaded pe dismiss karo — saari images wait nahi karega
            if (document.readyState === 'interactive' || document.readyState === 'complete') {
                dismiss();
            } else {
                document.addEventListener('DOMContentLoaded', dismiss);
            }

            // Safety net — maximum 3 seconds se zyada kabhi nahi
            setTimeout(dismiss, MAX_MS);

            // Browser back/forward pe page BFCache se restore ho sakta hai.
            // Is event me scripts re-run nahi hote, isliye UI state force-correct karo.
            window.addEventListener('pageshow', ensurePageVisible);

            // Internal link click pe preloader wapas show
            document.addEventListener('click', function(e) {
                var link = e.target.closest('a');
                if (!link) return;

                var href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('mailto:') ||
                    href.startsWith('tel:') || href.startsWith('javascript:') ||
                    link.target === '_blank' ||
                    e.ctrlKey || e.metaKey || e.shiftKey) return;

                try {
                    var url = new URL(href, window.location.origin);
                    if (url.origin !== window.location.origin) return;
                    if (url.pathname === window.location.pathname &&
                        url.search === window.location.search) return;
                } catch (_) {
                    return;
                }

                // Fallback overlay lagao agar preloader element na mile.
                var newLoader = document.getElementById('preloader');
                if (!newLoader) {
                    // Agar remove ho chuka hai, to simple dark overlay daal do
                    var overlay = document.createElement('div');
                    overlay.id = 'nav-preloader-overlay';
                    overlay.style.cssText = 'position:fixed;inset:0;z-index:99999;background:#1A1B2F;';
                    document.body.appendChild(overlay);
                } else {
                    showLoader(newLoader);
                }
            });
        })();
        /* ─────────────────────────────────────────────────────── */


        /* Navbar scroll effect */
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (nav) {
                nav.classList.toggle('scrolled', window.scrollY > 20);
            }
        });


        /* Fade-up animation */
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));


        /* Lazy loading images */
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('img[loading="lazy"]').forEach(img => {
                img.addEventListener('load', () => img.classList.add('loaded'));
                if (img.complete) img.classList.add('loaded');
            });
        });
    </script>

    <script src="{{ asset('assets/frontend/js/custom.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/allSlider.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/minislider.js') }}"></script>
    @yield('scripts')
</body>

</html>
