@extends('layouts.frontend')

@section('title', 'Frequently Asked Questions - ' . $settings['site_name'])
@section('meta_description', 'Find answers to common questions about our pet care services, adoption process, vaccinations, and grooming.')

@section('content')
<div class="page-hero">
    <div class="container">
        <h1>Frequently Asked Questions</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> /
            FAQs
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="faq-accordion">
            @forelse($faqs as $faq)
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        <span>{{ $faq->question }}</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>{{ $faq->answer }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                    <h4>No FAQs available</h4>
                    <p>Please check back later or contact us directly.</p>
                </div>
            @endforelse
        </div>

        <div style="text-align:center;margin-top:50px;">
            <div class="card" style="max-width:500px;margin:0 auto;padding:30px;text-align:left;">
                <h4 style="margin-bottom:15px;color:var(--clr-secondary);">Still have questions?</h4>
                <p style="margin-bottom:20px;color:#666;">Can't find the answer you're looking for? We're here to help!</p>
                <div style="display:flex;gap:15px;flex-wrap:wrap;">
                    <a href="{{ route('contact') }}" class="btn btn-primary">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                    <a href="tel:{{ $settings['site_phone'] }}" class="btn btn-outline">
                        <i class="fas fa-phone"></i> Call Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
function toggleFaq(button) {
    const faqItem = button.parentElement;
    const answer = faqItem.querySelector('.faq-answer');
    const icon = button.querySelector('i');

    // Close other open FAQs
    document.querySelectorAll('.faq-item').forEach(item => {
        if (item !== faqItem) {
            item.querySelector('.faq-answer').style.maxHeight = '0';
            item.querySelector('.faq-question').classList.remove('active');
            item.querySelector('.faq-question i').classList.remove('rotated');
        }
    });

    // Toggle current FAQ
    if (answer.style.maxHeight === '0px' || !answer.style.maxHeight) {
        answer.style.maxHeight = answer.scrollHeight + 'px';
        button.classList.add('active');
        icon.classList.add('rotated');
    } else {
        answer.style.maxHeight = '0';
        button.classList.remove('active');
        icon.classList.remove('rotated');
    }
}

// Add rotated class styles
const style = document.createElement('style');
style.textContent = `
    .faq-question i.rotated {
        transform: rotate(180deg);
    }
    .faq-question.active {
        background: rgba(255, 107, 53, 0.05);
        color: var(--clr-primary);
    }
`;
document.head.appendChild(style);
</script>
@endsection