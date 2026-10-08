@extends('layouts.frontend')

@section('meta_title', 'Pet Nutrition - ' . $settings['site_name'])
@section('meta_description',
    'Browse pet nutrition products by pet type and find the right food and supplements for your
    furry friend.')

@section('styles')
    <style>
        :root {
            --clr-primary: #FF6B35;
            --clr-secondary: #1E3E62;
            --clr-border: #E2E8F0;
            --clr-gray: #64748B;
            --clr-bg: #F8FAFC;
        }

        .nutrition-hero {
            padding: 56px 0;
            background: linear-gradient(140deg, #112A46 0%, #1E3E62 55%, #0F172A 100%);
            color: #fff;
            text-align: center;
        }

        .nutrition-hero h1 {
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 800;
            margin-bottom: 10px;
        }

        .nutrition-page {
            padding: 36px 0 72px;
            background: var(--clr-bg);
            min-height: 600px;
        }

        .pet-card-grid {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .pet-card {
            background: #fff;
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 10px 18px;
            cursor: pointer;
            transition: all .25s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 210px;

            box-shadow: 0 3px 8px rgba(0, 0, 0, .04);
        }

        .pet-card.active {
            border-color: var(--clr-primary);
            background: #FFF7ED;
            transform: translateY(-2px);
        }

        .pet-card-icon {
            font-size: 1.5rem;
            margin-bottom: 4px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 18px;
        }

        /* Compact + better card */
        .nutrition-card {
            background: #fff;
            border-radius: 16px;
            padding: 12px;
            border: 1px solid var(--clr-border);
            transition: all .25s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
            cursor: pointer;
        }

        .nutrition-card:hover {
            box-shadow: 0 12px 22px rgba(2, 6, 23, .10);
            transform: translateY(-3px);
        }

        .nutrition-media {
            position: relative;
            margin-bottom: 10px;
            border-radius: 12px;
            overflow: hidden;
            background: #f1f5f9;
        }

        .nutrition-main-image {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: contain;
            background: #fff;
        }

        .nutrition-thumbs {
            display: flex;
            gap: 6px;
            padding: 8px;
            background: rgba(255, 255, 255, .9);
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            transform: translateY(100%);
            transition: transform .25s ease;
            justify-content: center;
            backdrop-filter: blur(5px);
        }

        .nutrition-card:hover .nutrition-thumbs {
            transform: translateY(0);
        }

        .nutrition-thumb {
            width: 34px;
            height: 34px;
            border-radius: 6px;
            cursor: pointer;
            border: 2px solid #fff;
            object-fit: cover;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .08);
        }

        .nutrition-thumb.active,
        .nutrition-thumb:hover {
            border-color: var(--clr-primary);
        }

        .nutrition-card h3 {
            font-size: .98rem;
            font-weight: 700;
            margin: 4px 0 6px;
            color: #1e293b;
            line-height: 1.35;
            min-height: 42px;
        }

        .nutrition-card p {
            font-size: .82rem;
            color: var(--clr-gray);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 10px;
            flex-grow: 1;
        }

        .nutrition-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 8px;
        }

        .nutrition-price {
            color: #059669;
            font-weight: 800;
            font-size: 1rem;
        }

        .nutrition-empty {
            text-align: center;
            padding: 48px;
            background: #fff;
            border-radius: 14px;
            grid-column: 1 / -1;
        }

        /* Product Detail Modal */
        .pd-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, .55);
            z-index: 1050;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .pd-backdrop.show {
            display: flex;
        }

        .pd-modal {
            background: #fff;
            width: min(980px, 100%);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(0, 0, 0, .25);
            max-height: 92vh;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            /* Slightly more space for the image side */
        }

        .pd-left,
        .pd-right {
            padding: 16px;
            overflow: auto;
        }

        .pd-left {
            padding: 20px;
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            gap: 15px;
            overflow-y: auto;
            /* Agar content lamba ho to scroll karega */
        }

        .pd-main {
            width: 100%;
            height: 300px;
            aspect-ratio: 1/1;
            object-fit: contain;
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
            /* Image ko chhota hone se rokta hai */
        }

        .pd-thumbs {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-start;
            margin-top: 5px;
        }

        .pd-thumb {
            width: 60px;
            /* Thoda size adjust kiya */
            height: 60px;
            object-fit: cover;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .pd-thumb.active {
            border-color: var(--clr-primary);
        }

        .pd-actions {
            margin-top: 10px;
            display: flex;
            gap: 8px;
        }

        .pd-actions button {
            border: 1px solid #cbd5e1;
            background: #fff;
            border-radius: 8px;
            padding: 6px 10px;
            cursor: pointer;
        }

        .pd-title {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 8px;
            color: #0f172a;
        }

        .pd-price {
            color: #059669;
            font-size: 1.15rem;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .pd-desc {
            color: #334155;
            font-size: .95rem;
            line-height: 1.6;
            white-space: pre-line;
        }

        .pd-close {
            position: absolute;
            right: 10px;
            top: 10px;
            border: none;
            background: #fff;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .12);
            cursor: pointer;
            font-size: 20px;
            line-height: 1;
        }

        .pd-wrap {
            position: relative;
        }

        @media (max-width: 900px) {
            .pd-modal {
                grid-template-columns: 1fr;
                max-height: 95vh;
            }

            .pd-left {
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
                padding: 15px;
            }
        }

        @media (max-width: 900px) {
            .pd-modal {
                grid-template-columns: 1fr;
            }

            .pd-left {
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
            }
        }
    </style>
@endsection

@section('content')
    <section class="nutrition-hero">
        <div class="container">
            <h1>Pet Nutrition</h1>
            <p>Expert-curated food and supplements for your healthy pets.</p>
        </div>
    </section>

    <section class="nutrition-page">
        <div class="container">
            @if ($petTypes->isEmpty())
                <div class="nutrition-empty">
                    <h3>No categories found</h3>
                    <p>Please check back later.</p>
                </div>
            @else
                <div id="petCardGrid" class="pet-card-grid">
                    @foreach ($petTypes as $index => $petType)
                        <div class="pet-card {{ $index === 0 ? 'active' : '' }}" data-pet-id="{{ $petType->id }}">
                            <div class="pet-card-icon">{{ $petType->icon ?: '🐾' }}</div>
                            <span style="font-weight:600;">{{ $petType->name }}</span>
                        </div>
                    @endforeach
                </div>

                <div id="nutritionProducts" class="product-grid"></div>
            @endif
        </div>
    </section>

    {{-- Product Detail Modal --}}
    <div id="productDetailBackdrop" class="pd-backdrop" aria-hidden="true">
        <div class="pd-wrap">
            <button id="pdCloseBtn" class="pd-close" aria-label="Close modal">&times;</button>
            <div class="pd-modal">
                <div class="pd-left">
                    <img id="pdMainImage" class="pd-main" src="" alt="Product image">
                    <div id="pdThumbs" class="pd-thumbs"></div>
                    <div class="pd-actions">
                        <button type="button" id="pdPrevBtn">◀ Prev</button>
                        <button type="button" id="pdNextBtn">Next ▶</button>
                    </div>
                </div>
                <div class="pd-right">
                    <h2 id="pdTitle" class="pd-title"></h2>
                    <div id="pdPrice" class="pd-price"></div>
                    <div id="pdDesc" class="pd-desc"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productsByPet = @json($productsByPet);
            const productContainer = document.getElementById('nutritionProducts');
            const petCards = document.querySelectorAll('.pet-card');

            // Modal Elements
            const backdrop = document.getElementById('productDetailBackdrop');
            const pdMainImage = document.getElementById('pdMainImage');
            const pdThumbs = document.getElementById('pdThumbs');
            const pdTitle = document.getElementById('pdTitle');
            const pdPrice = document.getElementById('pdPrice');
            const pdDesc = document.getElementById('pdDesc');
            const pdCloseBtn = document.getElementById('pdCloseBtn');
            const pdPrevBtn = document.getElementById('pdPrevBtn');
            const pdNextBtn = document.getElementById('pdNextBtn');

            let activeProduct = null;
            let activeImageIndex = 0;

            function escapeHtml(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');
            }

            // Card image switch (small thumbnails on card)
            window.changeImg = function(thumb, newSrc) {
                const card = thumb.closest('.nutrition-card');
                const main = card.querySelector('.nutrition-main-image');
                if (main) main.src = newSrc;

                card.querySelectorAll('.nutrition-thumb').forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');
            };

            function formatPrice(showPrice, price) {
                if (!showPrice || !price) return '';
                return `₹${Number(price).toLocaleString('en-IN')}`;
            }

            function renderProducts(petId) {
                const items = productsByPet[String(petId)] || [];

                if (!items.length) {
                    productContainer.innerHTML =
                        '<div class="nutrition-empty"><h3>No products found for this category.</h3></div>';
                    return;
                }

                productContainer.innerHTML = items.map((item, index) => {
                    const images = Array.isArray(item.image_urls) ? item.image_urls.filter(Boolean) : [];
                    const mainImg = item.primary_image_url || images[0] ||
                        'https://via.placeholder.com/500x500?text=No+Image';

                    const thumbs = images.length > 1 ?
                        `<div class="nutrition-thumbs">
                    ${images.map((url) => `
                                        <img src="${escapeHtml(url)}"
                                             class="nutrition-thumb ${url === mainImg ? 'active' : ''}"
                                             onmouseover="changeImg(this, '${escapeHtml(url)}')"
                                             alt="thumbnail">
                                    `).join('')}
                   </div>` :
                        '';

                    const price = formatPrice(item.show_price, item.price);

                    return `
                <article class="nutrition-card js-product-card"
                         data-item='${escapeHtml(JSON.stringify(item))}'>
                    <div class="nutrition-media">
                        <img class="nutrition-main-image" src="${escapeHtml(mainImg)}" alt="${escapeHtml(item.name)}">
                        ${thumbs}
                    </div>
                    <h3>${escapeHtml(item.name)}</h3>
                    <p>${escapeHtml(item.description || "Premium balanced nutrition for your pet's health.")}</p>
                   <div class="nutrition-footer">
                        <div class="nutrition-price">${price}</div>

                        <div style="display:flex;gap:6px;">
                            <button type="button"
                                class="btn btn-sm btn-outline-primary"
                                style="border-radius:8px;font-size:12px;">
                                View
                            </button>

                           <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) }}?text=${encodeURIComponent('Hi, I want to buy: ' + item.name)}"
                            target="_blank"
                            style="
                                background:#25D366;
                                color:#fff;
                                padding:6px 12px;
                                border-radius:8px;
                                font-size:12px;
                                text-decoration:none;
                                display:inline-flex;
                                align-items:center;
                                gap:5px;
                                font-weight:600;
                            ">
                            <i class="fab fa-whatsapp"></i> Buy
                            </a>
                        </div>
                    </div>
                </article>
            `;
                }).join('');

                bindCardClicks();
            }

            function setModalImage(index) {
                if (!activeProduct) return;
                const imgs = (Array.isArray(activeProduct.image_urls) && activeProduct.image_urls.length) ?
                    activeProduct.image_urls : [activeProduct.primary_image_url].filter(Boolean);

                if (!imgs.length) return;

                activeImageIndex = (index + imgs.length) % imgs.length;
                pdMainImage.src = imgs[activeImageIndex];

                pdThumbs.querySelectorAll('.pd-thumb').forEach((el, i) => {
                    el.classList.toggle('active', i === activeImageIndex);
                });
            }

            function openModal(item) {
                activeProduct = item;
                const imgs = (Array.isArray(item.image_urls) && item.image_urls.length) ?
                    item.image_urls : [item.primary_image_url].filter(Boolean);

                activeImageIndex = 0;
                pdTitle.textContent = item.name || 'Product';
                pdPrice.textContent = formatPrice(item.show_price, item.price);
                pdDesc.textContent = item.description || 'No detailed description available for this product.';

                pdThumbs.innerHTML = imgs.map((url, i) => `
            <img src="${escapeHtml(url)}" class="pd-thumb ${i === 0 ? 'active' : ''}" data-idx="${i}" alt="thumb ${i+1}">
        `).join('');

                pdThumbs.querySelectorAll('.pd-thumb').forEach(thumb => {
                    thumb.addEventListener('click', function() {
                        setModalImage(Number(this.dataset.idx));
                    });
                });

                if (imgs.length) {
                    pdMainImage.src = imgs[0];
                } else {
                    pdMainImage.src = 'https://via.placeholder.com/700x700?text=No+Image';
                }

                backdrop.classList.add('show');
                backdrop.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                backdrop.classList.remove('show');
                backdrop.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            function bindCardClicks() {
                document.querySelectorAll('.js-product-card').forEach(card => {
                    card.addEventListener('click', function(e) {
                        // Card click from anywhere open modal
                        const raw = this.getAttribute('data-item');
                        if (!raw) return;
                        try {
                            const parsed = JSON.parse(raw);
                            openModal(parsed);
                        } catch (err) {
                            console.error('Invalid product payload', err);
                        }
                    });
                });
            }

            // Pet tab switch
            petCards.forEach(card => {
                card.addEventListener('click', function() {
                    petCards.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    renderProducts(this.dataset.petId);
                });
            });

            // Modal controls
            pdCloseBtn.addEventListener('click', closeModal);
            backdrop.addEventListener('click', function(e) {
                if (e.target === backdrop) closeModal();
            });

            pdPrevBtn.addEventListener('click', function() {
                setModalImage(activeImageIndex - 1);
            });

            pdNextBtn.addEventListener('click', function() {
                setModalImage(activeImageIndex + 1);
            });

            document.addEventListener('keydown', function(e) {
                if (!backdrop.classList.contains('show')) return;
                if (e.key === 'Escape') closeModal();
                if (e.key === 'ArrowLeft') setModalImage(activeImageIndex - 1);
                if (e.key === 'ArrowRight') setModalImage(activeImageIndex + 1);
            });

            // Initial load
            if (petCards.length) {
                renderProducts(petCards[0].dataset.petId);
            }
        });
    </script>
@endsection
