@extends('layouts.frontend')

@section('meta_title', $page->meta_title ?? 'About Us - ' . $settings['site_name'])
@section('meta_description', $page->meta_description ?? 'Learn about our story, mission, values, and the passionate team behind ' . $settings['site_name'] . ' — your trusted partner for premium pet care.')
@section('meta_keywords', $page->meta_keywords ?? 'about us, pet care team, founders, our story, pet store mission')
@section('canonical', route('about'))

{{-- Open Graph image --}}
@if($page && $page->image)
    @section('og_image', $page->image_url)
@endif

@section('content')

{{-- ===== HERO SECTION ===== --}}
<div class="about-hero">
    <div class="about-hero-bg">
        @if($page && $page->image)
            <img src="{{ $page->image_url }}" alt="{{ $page->title ?? 'About Us' }}" class="about-hero-img">
        @endif
        <div class="about-hero-overlay"></div>
    </div>
    <div class="container about-hero-content">
        <div class="about-hero-badge fade-up">Our Story</div>
        <h1 class="about-hero-title fade-up">
            @if($page && $page->title)
                {{ $page->title }}
            @else
                Passionate About<br>Every Paw & Tail
            @endif
        </h1>
        <p class="about-hero-sub fade-up">
            From humble beginnings to your city's most trusted pet care partner — here's the journey that drives everything we do.
        </p>
        <div class="about-hero-breadcrumb fade-up">
            <a href="{{ route('home') }}">Home</a>
            <span><i class="fas fa-chevron-right"></i></span>
            <span>About Us</span>
        </div>
    </div>
</div>

{{-- ===== STATS BAR ===== --}}
<div class="about-stats-bar">
    <div class="container">
        <div class="about-stats-grid">
            <div class="about-stat fade-up">
                <span class="about-stat-number">5000+</span>
                <span class="about-stat-label">Happy Pet Families</span>
            </div>
            <div class="about-stat fade-up">
                <span class="about-stat-number">50+</span>
                <span class="about-stat-label">Expert Vets & Groomers</span>
            </div>
            <div class="about-stat fade-up">
                <span class="about-stat-number">10+</span>
                <span class="about-stat-label">Cities Served</span>
            </div>
            <div class="about-stat fade-up">
                <span class="about-stat-number">99%</span>
                <span class="about-stat-label">Customer Satisfaction</span>
            </div>
        </div>
    </div>
</div>

{{-- ===== OUR STORY SECTION ===== --}}
<section class="section about-story-section">
    <div class="container">
        <div class="about-story-wrap">
            <div class="about-story-text fade-up">
                <span class="section-badge"><i class="fas fa-book-open"></i> Our Journey</span>
                <h2>How It All Began</h2>
                @if($page && $page->content)
                    <div class="about-story-content">{!! $page->content !!}</div>
                @else
                    <div class="about-story-content">
                        <p>It all started with a simple question: <em>"Why is it so hard to find trustworthy, affordable, and loving care for our pets?"</em></p>
                        <p>Our founders — a group of passionate pet lovers — faced this exact struggle. After spending years searching for reliable vets, groomers, and boarding facilities, they decided to build the solution themselves.</p>
                        <p>What began as a small vaccination clinic in a single city has grown into a full-service pet care ecosystem, connecting thousands of families with certified professionals across multiple cities. Every service we offer is rooted in one belief: <strong>your pet deserves the very best.</strong></p>
                        <p>Today, {{ $settings['site_name'] }} stands as a testament to that founding vision — a place where pets are treated like family, and their parents can have complete peace of mind.</p>
                    </div>
                @endif
            </div>
            <div class="about-story-visual fade-up">
                <div class="about-story-card">
                    <div class="about-story-icon-wrap">
                        <i class="fas fa-heart"></i>
                    </div>
                    <blockquote>
                        "We didn't build a business. We built a home for every pet and every family that loves them."
                    </blockquote>
                    <cite>— The {{ $settings['site_name'] }} Founders</cite>
                </div>
                <div class="about-timeline">
                    <div class="about-timeline-item">
                        <div class="about-timeline-dot"></div>
                        <div class="about-timeline-body">
                            <strong>Year 1</strong>
                            <p>First vaccination clinic opened</p>
                        </div>
                    </div>
                    <div class="about-timeline-item">
                        <div class="about-timeline-dot"></div>
                        <div class="about-timeline-body">
                            <strong>Year 2</strong>
                            <p>Expanded to grooming &amp; boarding services</p>
                        </div>
                    </div>
                    <div class="about-timeline-item">
                        <div class="about-timeline-dot"></div>
                        <div class="about-timeline-body">
                            <strong>Year 3</strong>
                            <p>Launched pet adoption program</p>
                        </div>
                    </div>
                    <div class="about-timeline-item">
                        <div class="about-timeline-dot"></div>
                        <div class="about-timeline-body">
                            <strong>Today</strong>
                            <p>Serving 10+ cities &amp; growing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== MISSION & VALUES SECTION ===== --}}
<section class="section" style="background:var(--clr-light-gray);">
    <div class="container">
        <div class="section-header fade-up">
            <span class="section-badge"><i class="fas fa-compass"></i> What Drives Us</span>
            <h2>Our Mission &amp; Core Values</h2>
            <p>Every decision, every service, every interaction is guided by these principles</p>
        </div>
        <div class="about-values-grid">
            <div class="about-value-card fade-up">
                <div class="about-value-icon" style="background:rgba(255,107,53,0.1);color:var(--clr-primary);">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Compassion First</h3>
                <p>We treat every animal with the same love and care we'd give our own. Compassion is not a policy — it's our culture.</p>
            </div>
            <div class="about-value-card fade-up">
                <div class="about-value-icon" style="background:rgba(6,214,160,0.1);color:var(--clr-accent);">
                    <i class="fas fa-award"></i>
                </div>
                <h3>Excellence Always</h3>
                <p>From certified vets to premium products — we refuse to compromise on the quality of care your pet receives.</p>
            </div>
            <div class="about-value-card fade-up">
                <div class="about-value-icon" style="background:rgba(59,130,246,0.1);color:#3B82F6;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Trust &amp; Safety</h3>
                <p>Every professional on our platform is background-verified and certified. Your pet's safety is our highest priority.</p>
            </div>
            <div class="about-value-card fade-up">
                <div class="about-value-icon" style="background:rgba(245,158,11,0.1);color:#F59E0B;">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h3>Community Impact</h3>
                <p>We actively support animal shelters, run free vaccination drives, and promote responsible pet adoption across our cities.</p>
            </div>
            <div class="about-value-card fade-up">
                <div class="about-value-icon" style="background:rgba(139,92,246,0.1);color:#8B5CF6;">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <h3>Innovation</h3>
                <p>We continuously evolve — from online booking to WhatsApp updates — making pet care smarter and more convenient.</p>
            </div>
            <div class="about-value-card fade-up">
                <div class="about-value-icon" style="background:rgba(236,72,153,0.1);color:#EC4899;">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Family Bond</h3>
                <p>We understand that pets are family. Our services are built around that bond — supporting it at every stage of life.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== YOUTUBE VIDEO SECTION ===== --}}
@if($page && $page->youtube_video_url)
<section class="section about-video-section">
    <div class="container">
        <div class="section-header fade-up">
            <span class="section-badge"><i class="fab fa-youtube"></i> Watch Our Story</span>
            <h2>See What We're About</h2>
            <p>A glimpse into our world — the team, the animals, and the moments that matter</p>
        </div>
        <div class="about-video-wrap fade-up">
            <div class="about-video-frame">
                <iframe
                    src="{{ $page->youtube_video_url }}"
                    title="{{ $settings['site_name'] }} — Our Story"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ===== FOUNDERS SECTION ===== --}}
@if($founders->count())
<section class="section" style="background:var(--clr-cream);">
    <div class="container">
        <div class="section-header fade-up">
            <span class="section-badge"><i class="fas fa-star"></i> The People Behind It</span>
            <h2>Meet Our Founders</h2>
            <p>The visionaries who turned a love for animals into a movement that's changing pet care across India</p>
        </div>
        <div class="founders-grid">
            @foreach($founders as $founder)
            <div class="founder-card fade-up">
                <div class="founder-photo-wrap">
                    @if($founder->image)
                        <img src="{{ $founder->image_url }}" alt="{{ $founder->name }}" class="founder-photo" loading="lazy">
                    @else
                        <div class="founder-photo-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    @if($founder->linkedin_url || $founder->twitter_url || $founder->instagram_url)
                    <div class="founder-social">
                        @if($founder->linkedin_url)
                            <a href="{{ $founder->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="founder-social-link" aria-label="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        @endif
                        @if($founder->twitter_url)
                            <a href="{{ $founder->twitter_url }}" target="_blank" rel="noopener noreferrer" class="founder-social-link" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                        @endif
                        @if($founder->instagram_url)
                            <a href="{{ $founder->instagram_url }}" target="_blank" rel="noopener noreferrer" class="founder-social-link" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="founder-info">
                    <h3 class="founder-name">{{ $founder->name }}</h3>
                    <span class="founder-role">{{ $founder->role }}</span>
                    @if($founder->bio)
                        <p class="founder-bio">{{ $founder->bio }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== WHY CHOOSE US SECTION ===== --}}
<section class="section about-why-section">
    <div class="container">
        <div class="about-why-wrap">
            <div class="about-why-text fade-up">
                <span class="section-badge"><i class="fas fa-check-circle"></i> Why Choose Us</span>
                <h2>The {{ $settings['site_name'] }} Difference</h2>
                <p style="color:var(--clr-gray);margin-bottom:32px;">Thousands of families trust us every day. Here's what makes us different from the rest.</p>

                <div class="about-why-list">
                    <div class="about-why-item">
                        <div class="about-why-icon"><i class="fas fa-certificate"></i></div>
                        <div>
                            <strong>Certified Professionals</strong>
                            <p>All our vets, groomers, and trainers are licensed, experienced, and passionate about animals.</p>
                        </div>
                    </div>
                    <div class="about-why-item">
                        <div class="about-why-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <strong>Multi-City Presence</strong>
                            <p>Whether you're in the heart of the city or the suburbs, we're never far away.</p>
                        </div>
                    </div>
                    <div class="about-why-item">
                        <div class="about-why-icon"><i class="fas fa-mobile-alt"></i></div>
                        <div>
                            <strong>Seamless Online Booking</strong>
                            <p>Book appointments, track status, and get WhatsApp confirmations — all in minutes.</p>
                        </div>
                    </div>
                    <div class="about-why-item">
                        <div class="about-why-icon"><i class="fas fa-rupee-sign"></i></div>
                        <div>
                            <strong>Transparent Pricing</strong>
                            <p>No hidden fees. Clear, upfront pricing so you always know what you're paying for.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="about-why-visual fade-up">
                <div class="about-why-img-wrap">
                    <div class="about-why-badge-float about-why-badge-1">
                        <i class="fas fa-paw"></i> 5000+ Pets Cared For
                    </div>
                    <div class="about-why-badge-float about-why-badge-2">
                        <i class="fas fa-star"></i> 4.9 / 5 Rating
                    </div>
                    <div class="about-why-img-inner">
                        <i class="fas fa-paw" style="font-size:5rem;color:var(--clr-primary);opacity:0.15;"></i>
                        <i class="fas fa-heart" style="font-size:3rem;color:var(--clr-accent);opacity:0.2;position:absolute;top:20%;right:15%;"></i>
                        <i class="fas fa-star" style="font-size:2rem;color:#F59E0B;opacity:0.25;position:absolute;bottom:25%;left:10%;"></i>
                        <div style="font-size:6rem;line-height:1;">🐾</div>
                        <div style="font-size:4rem;margin-top:-10px;">🐶🐱</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CTA SECTION ===== --}}
<section class="about-cta-section">
    <div class="container">
        <div class="about-cta-box fade-up">
            <span class="section-badge" style="background:rgba(255,255,255,0.2);color:#fff;"><i class="fas fa-paw"></i> Join Our Family</span>
            <h2>Ready to Give Your Pet the Best?</h2>
            <p>Book a service today and experience the {{ $settings['site_name'] }} difference for yourself.</p>
            <div class="about-cta-actions">
                <a href="{{ route('vaccination') }}" class="btn btn-white">
                    <i class="fas fa-syringe"></i> Book Vaccination
                </a>
                <a href="{{ route('adoption.index') }}" class="btn btn-white-outline">
                    <i class="fas fa-home"></i> Adopt a Pet
                </a>
            </div>
        </div>
    </div>
</section>

<style>
/* ============================================
   ABOUT US PAGE STYLES
   ============================================ */

/* Hero */
.about-hero {
    position: relative;
    min-height: 420px;
    display: flex;
    align-items: center;
    background: var(--clr-secondary);
    overflow: hidden;
}
.about-hero-bg {
    position: absolute;
    inset: 0;
}
.about-hero-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.25;
}
.about-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(45,49,66,0.92) 0%, rgba(255,107,53,0.6) 100%);
}
.about-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    padding: 80px 20px;
}
.about-hero-badge {
    display: inline-block;
    background: rgba(255,107,53,0.25);
    color: var(--clr-primary);
    border: 1px solid rgba(255,107,53,0.4);
    padding: 6px 20px;
    border-radius: 50px;
    font-size: 0.82rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    margin-bottom: 20px;
}
.about-hero-title {
    font-family: var(--font-display);
    font-size: clamp(2rem, 5vw, 3.4rem);
    color: #fff;
    line-height: 1.2;
    margin-bottom: 16px;
}
.about-hero-sub {
    color: rgba(255,255,255,0.75);
    font-size: 1.05rem;
    max-width: 560px;
    margin: 0 auto 28px;
    line-height: 1.7;
}
.about-hero-breadcrumb {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    color: rgba(255,255,255,0.55);
    font-size: 0.88rem;
}
.about-hero-breadcrumb a {
    color: rgba(255,255,255,0.75);
    text-decoration: none;
}
.about-hero-breadcrumb a:hover { color: #fff; }
.about-hero-breadcrumb i { font-size: 0.65rem; }

/* Stats bar */
.about-stats-bar {
    background: var(--clr-primary);
    padding: 0;
}
.about-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
}
.about-stat {
    padding: 28px 20px;
    text-align: center;
    border-right: 1px solid rgba(255,255,255,0.15);
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.about-stat:last-child { border-right: none; }
.about-stat-number {
    font-size: clamp(1.6rem, 3vw, 2.4rem);
    font-weight: 800;
    color: #fff;
    line-height: 1;
}
.about-stat-label {
    font-size: 0.82rem;
    color: rgba(255,255,255,0.8);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

/* Story section */
.about-story-wrap {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: start;
}
.about-story-text h2 {
    font-family: var(--font-display);
    font-size: clamp(1.6rem, 3vw, 2.4rem);
    color: var(--clr-dark);
    margin: 16px 0 20px;
}
.about-story-content p {
    color: var(--clr-gray);
    line-height: 1.8;
    margin-bottom: 16px;
    font-size: 1.02rem;
}
.about-story-content strong { color: var(--clr-dark); }
.about-story-content em { color: var(--clr-primary); font-style: italic; }
.about-story-card {
    background: linear-gradient(135deg, var(--clr-primary) 0%, var(--clr-primary-dark) 100%);
    border-radius: var(--radius-lg);
    padding: 36px 32px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
}
.about-story-card::before {
    content: '"';
    position: absolute;
    top: -10px;
    left: 20px;
    font-size: 8rem;
    color: rgba(255,255,255,0.1);
    font-family: Georgia, serif;
    line-height: 1;
}
.about-story-icon-wrap {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.2rem;
    margin-bottom: 16px;
}
.about-story-card blockquote {
    color: #fff;
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-style: italic;
    line-height: 1.6;
    margin: 0 0 12px;
}
.about-story-card cite {
    color: rgba(255,255,255,0.7);
    font-size: 0.85rem;
    font-style: normal;
    font-weight: 600;
}
.about-timeline {
    display: flex;
    flex-direction: column;
    gap: 0;
    padding-left: 12px;
    border-left: 2px solid var(--clr-border);
}
.about-timeline-item {
    display: flex;
    gap: 16px;
    padding-bottom: 20px;
    position: relative;
}
.about-timeline-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--clr-primary);
    flex-shrink: 0;
    margin-top: 4px;
    margin-left: -18px;
    box-shadow: 0 0 0 3px rgba(255,107,53,0.2);
}
.about-timeline-body strong {
    display: block;
    color: var(--clr-dark);
    font-weight: 700;
    font-size: 0.9rem;
    margin-bottom: 2px;
}
.about-timeline-body p {
    color: var(--clr-gray);
    font-size: 0.88rem;
    margin: 0;
}

/* Values */
.about-values-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-top: 40px;
}
.about-value-card {
    background: #fff;
    border-radius: var(--radius-md);
    padding: 28px 24px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    border: 1px solid var(--clr-border);
}
.about-value-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}
.about-value-icon {
    width: 52px;
    height: 52px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    margin-bottom: 16px;
}
.about-value-card h3 {
    font-size: 1rem;
    font-weight: 700;
    color: var(--clr-dark);
    margin-bottom: 10px;
}
.about-value-card p {
    color: var(--clr-gray);
    font-size: 0.9rem;
    line-height: 1.65;
    margin: 0;
}

/* Video section */
.about-video-section { background: var(--clr-dark); }
.about-video-section .section-badge { background: rgba(255,107,53,0.2); color: var(--clr-primary); }
.about-video-section .section-header h2 { color: #fff; }
.about-video-section .section-header p { color: rgba(255,255,255,0.6); }
.about-video-wrap { margin-top: 40px; }
.about-video-frame {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: 0 24px 80px rgba(0,0,0,0.5);
    max-width: 900px;
    margin: 0 auto;
}
.about-video-frame iframe {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    border: none;
}

/* Founders */
.founders-grid {
    display: grid;
    ;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 32px;
    margin-top: 48px;
}

/* Founders */
.founders-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 32px;
    margin-top: 48px;
    max-width: 820px;
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 600px) {
    .founders-grid {
        grid-template-columns: 1fr;
        max-width: 320px;
    }
}

.founder-card {
    background: #fff;
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--clr-border);
    transition: var(--transition);
    text-align: center;
}

.founder-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-lg);
}

.founder-photo-wrap {
    position: relative;
    background: linear-gradient(135deg, var(--clr-light-gray) 0%, var(--clr-cream) 100%);
    padding-top: 32px;
    padding-bottom: 16px;
    margin-bottom: 10px;
}

.founder-photo {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    display: block;
    margin: 0 auto;
    
}

.founder-photo-placeholder {
    width: 140px;
    height: 140px;
    border-radius: 50%;
    background: var(--clr-light-gray);
    border: 4px solid #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: var(--clr-gray);
    margin: 0 auto;
}

.founder-social {
    display: flex;
    justify-content: center;
    gap: 8px;
    padding: 16px 0 0;
}

.founder-social-link {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #fff;
    border: 1px solid var(--clr-border);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--clr-gray);
    font-size: 0.85rem;
    text-decoration: none;
    transition: var(--transition);
}

.founder-social-link:hover {
    background: var(--clr-primary);
    border-color: var(--clr-primary);
    color: #fff;
    transform: translateY(-2px);
}

.founder-info {
    padding: 20px 24px 28px;
}

.founder-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--clr-dark);
    margin: 0 0 6px;
}

.founder-role {
    display: inline-block;
    background: rgba(255,107,53,0.1);
    color: var(--clr-primary);
    border-radius: 50px;
    padding: 3px 14px;
    font-size: 0.78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 14px;
}

.founder-bio {
    color: var(--clr-gray);
    font-size: 0.9rem;
    line-height: 1.65;
    margin: 0;
}

/* Why choose us */
.about-why-wrap {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 64px;
    align-items: center;
}
.about-why-text h2 {
    font-family: var(--font-display);
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    color: var(--clr-dark);
    margin: 16px 0 8px;
}
.about-why-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.about-why-item {
    display: flex;
    gap: 16px;
    align-items: flex-start;
}
.about-why-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--radius-sm);
    background: rgba(255,107,53,0.1);
    color: var(--clr-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.about-why-item strong {
    display: block;
    color: var(--clr-dark);
    font-weight: 700;
    font-size: 0.95rem;
    margin-bottom: 4px;
}
.about-why-item p {
    color: var(--clr-gray);
    font-size: 0.9rem;
    line-height: 1.6;
    margin: 0;
}
.about-why-img-wrap {
    position: relative;
    background: linear-gradient(135deg, var(--clr-warm) 0%, #FFF3E0 100%);
    border-radius: var(--radius-xl);
    padding: 48px 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 340px;
    border: 1px solid var(--clr-border);
    overflow: visible;
}
.about-why-img-inner {
    text-align: center;
    position: relative;
}
.about-why-badge-float {
    position: absolute;
    background: #fff;
    border-radius: 50px;
    padding: 8px 18px;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--clr-dark);
    box-shadow: var(--shadow-md);
    white-space: nowrap;
    z-index: 2;
}
.about-why-badge-float i { color: var(--clr-primary); margin-right: 6px; }
.about-why-badge-1 { top: -16px; left: -20px; }
.about-why-badge-2 { bottom: -16px; right: -20px; }

/* CTA section */
.about-cta-section {
    background: linear-gradient(135deg, var(--clr-primary) 0%, var(--clr-primary-dark) 100%);
    padding: 80px 0;
}
.about-cta-box {
    text-align: center;
}
.about-cta-box h2 {
    font-family: var(--font-display);
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    color: #fff;
    margin: 16px 0 12px;
}
.about-cta-box p {
    color: rgba(255,255,255,0.8);
    font-size: 1.05rem;
    margin-bottom: 32px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}
.about-cta-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}
.btn-white {
    background: #fff;
    color: var(--clr-primary);
    padding: 14px 32px;
    border-radius: var(--radius-md);
    font-weight: 700;
    font-size: 0.95rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.btn-white:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}
.btn-white-outline {
    background: transparent;
    color: #fff;
    border: 2px solid rgba(255,255,255,0.6);
    padding: 12px 32px;
    border-radius: var(--radius-md);
    font-weight: 700;
    font-size: 0.95rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: var(--transition);
}
.btn-white-outline:hover {
    background: rgba(255,255,255,0.15);
    border-color: #fff;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 992px) {
    .about-stats-grid { grid-template-columns: repeat(2, 1fr); }
    .about-stat { border-right: none; border-bottom: 1px solid rgba(255,255,255,0.15); }
    .about-story-wrap { grid-template-columns: 1fr; gap: 40px; }
    .about-values-grid { grid-template-columns: repeat(2, 1fr); }
    .about-why-wrap { grid-template-columns: 1fr; gap: 40px; }
    .about-why-img-wrap { min-height: 260px; }
}
@media (max-width: 640px) {
    .about-stats-grid { grid-template-columns: repeat(2, 1fr); }
    .about-values-grid { grid-template-columns: 1fr; }
    .founders-grid { grid-template-columns: 1fr; max-width: 320px; margin-left: auto; margin-right: auto; }
    .about-cta-actions { flex-direction: column; align-items: center; }
    .about-why-badge-float { display: none; }
}
</style>

@endsection
