/* =====================================================
   allSlider.js  —  All sliders in one place
   Sections: HERO | BLOG | TESTIMONIAL | GALLERY | LIGHTBOX
   ===================================================== */

document.addEventListener("DOMContentLoaded", function () {

    /* ============================================================
       HERO SLIDER
       Simple auto-slide + dot nav. window.goToSlide() called from Blade.
    ============================================================ */
    (function () {
        let currentSlide = 0;
        const slider = document.getElementById("heroSlider");
        const dots   = document.querySelectorAll(".hero-dot");
        const total  = window.totalSlides || 0;

        if (!slider || total <= 1) return;

        function goToSlide(index) {
            currentSlide = index;
            slider.style.transform = `translateX(-${index * 100}%)`;
            dots.forEach((dot, i) => dot.classList.toggle("active", i === index));
        }

        setInterval(() => goToSlide((currentSlide + 1) % total), 5500);

        window.goToSlide = goToSlide;
    })();


    /* ============================================================
       BLOG SLIDER
       Infinite loop, px-based translation, drag + touch, auto-slide.
       window.moveBlogSlider(dir) called from blade onclick.
    ============================================================ */
    (function () {
        const track = document.getElementById("blogSlider");
        const wrap  = document.getElementById("blogSliderWrap");
        if (!track || !wrap) return;

        const realSlides = Array.from(track.querySelectorAll(".blog-slide"));
        const total = realSlides.length;
        if (total === 0) return;

        let visible        = 0;
        let stepPx         = 0;
        let currentIndex   = 0;
        let currentPx      = 0;
        let prevPx         = 0;
        let isTransitioning = false;
        let isDragging     = false;
        let startX         = 0;
        let autoTimer      = null;
        let initialized    = false;

        function getVisible() {
            if (window.innerWidth >= 992) return 3;
            if (window.innerWidth >= 600) return 2;
            return 1;
        }

        function calcStep() {
            const first = track.querySelector(".blog-slide");
            if (!first) return 0;
            const gap = parseFloat(window.getComputedStyle(track).columnGap) || 0;
            return first.offsetWidth + gap;
        }

        function init() {
            track.querySelectorAll(".blog-slide.clone").forEach(c => c.remove());
            visible = getVisible();

            const navs = wrap.querySelectorAll(".blog-slider-nav");
            if (total <= visible) {
                navs.forEach(n => (n.style.display = "none"));
                initialized = false;
                return;
            }
            navs.forEach(n => (n.style.display = ""));

            const headClones = realSlides.slice(-visible).map(s => {
                const c = s.cloneNode(true); c.classList.add("clone"); return c;
            });
            const tailClones = realSlides.slice(0, visible).map(s => {
                const c = s.cloneNode(true); c.classList.add("clone"); return c;
            });
            track.prepend(...headClones);
            track.append(...tailClones);

            stepPx       = calcStep();
            currentIndex = visible;
            currentPx    = -currentIndex * stepPx;
            track.style.transition = "none";
            track.style.transform  = `translateX(${currentPx}px)`;
            initialized = true;
        }

        function moveTo(index, animated) {
            currentIndex = index;
            currentPx    = -currentIndex * stepPx;
            track.style.transition = animated ? "transform 0.5s ease" : "none";
            track.style.transform  = `translateX(${currentPx}px)`;
        }

        function slide(dir) {
            if (!initialized || isTransitioning || isDragging) return;
            isTransitioning = true;
            moveTo(currentIndex + dir, true);
            setTimeout(() => {
                if (currentIndex >= total + visible)   moveTo(visible, false);
                else if (currentIndex < visible)       moveTo(total + visible - 1, false);
                setTimeout(() => { isTransitioning = false; }, 50);
            }, 500);
        }

        function getX(e) {
            return e.type.includes("mouse") ? e.pageX : e.touches[0].clientX;
        }

        function dragStart(e) {
            if (!initialized || isTransitioning) return;
            // Let nav button taps through — don't hijack their click
            if (e.target.closest(".blog-slider-nav")) return;
            isDragging = true;
            startX = getX(e);
            prevPx = currentPx;
            track.style.transition = "none";
            clearAuto();
            e.preventDefault();
        }

        function dragMove(e) {
            if (!isDragging) return;
            e.preventDefault();
            const diff = getX(e) - startX;
            currentPx = prevPx + diff;
            track.style.transform = `translateX(${currentPx}px)`;
            wrap.style.cursor = Math.abs(diff) > 5 ? "grabbing" : "grab";
        }

        function dragEnd() {
            if (!isDragging) return;
            isDragging = false;
            wrap.style.cursor = "grab";
            track.style.transition = "transform 0.3s ease";

            const diff      = currentPx - prevPx;
            const threshold = stepPx / 4;

            if      (diff >  threshold) slide(-1);
            else if (diff < -threshold) slide(1);
            else {
                currentPx = prevPx;
                track.style.transform = `translateX(${currentPx}px)`;
            }
            startAuto();
        }

        function clearAuto() { if (autoTimer) { clearInterval(autoTimer); autoTimer = null; } }
        function startAuto() {
            clearAuto();
            if (window.innerWidth >= 600)
                autoTimer = setInterval(() => slide(1), 3500);
        }

        wrap.addEventListener("mousedown",  dragStart);
        wrap.addEventListener("mousemove",  dragMove);
        wrap.addEventListener("mouseup",    dragEnd);
        wrap.addEventListener("mouseleave", dragEnd);
        wrap.addEventListener("touchstart", dragStart, { passive: false });
        wrap.addEventListener("touchmove",  dragMove,  { passive: false });
        wrap.addEventListener("touchend",   dragEnd);
        wrap.addEventListener("contextmenu", e => { if (isDragging) e.preventDefault(); });
        wrap.style.cursor = "grab";
        wrap.querySelectorAll("img").forEach(img =>
            img.addEventListener("dragstart", e => e.preventDefault())
        );

        let resizeTimer;
        window.addEventListener("resize", () => {
            clearTimeout(resizeTimer);
            clearAuto();
            resizeTimer = setTimeout(() => { init(); startAuto(); }, 200);
        });

        init();
        startAuto();

        window.moveBlogSlider = dir => slide(dir);
    })();


    /* ============================================================
       TESTIMONIAL SLIDER
       Infinite loop, px-based translation, drag + touch, auto-slide.
       window.moveTestimonialSlider(dir) called from blade onclick.
    ============================================================ */
    (function () {
        const track = document.getElementById("testimonialSlider");
        const wrap  = document.querySelector(".testimonial-slider-wrap");
        if (!track || !wrap) return;

        const realSlides = Array.from(track.querySelectorAll(".testimonial-slide"));
        const total = realSlides.length;
        if (total === 0) return;

        let visible        = 0;
        let stepPx         = 0;
        let currentIndex   = 0;
        let currentPx      = 0;
        let prevPx         = 0;
        let isTransitioning = false;
        let isDragging     = false;
        let startX         = 0;
        let autoTimer      = null;
        let initialized    = false;

        function getVisible() {
            if (window.innerWidth >= 992) return 3;
            if (window.innerWidth >= 600) return 2;
            return 1;
        }

        function calcStep() {
            const first = track.querySelector(".testimonial-slide");
            if (!first) return 0;
            const gap = parseFloat(window.getComputedStyle(track).columnGap) || 0;
            return first.offsetWidth + gap;
        }

        function init() {
            track.querySelectorAll(".testimonial-slide.clone").forEach(c => c.remove());
            visible = getVisible();

            const navs = document.querySelectorAll(".testimonial-slider-nav");
            if (total <= visible) {
                navs.forEach(n => (n.style.display = "none"));
                initialized = false;
                return;
            }
            navs.forEach(n => (n.style.display = ""));

            const headClones = realSlides.slice(-visible).map(s => {
                const c = s.cloneNode(true); c.classList.add("clone"); return c;
            });
            const tailClones = realSlides.slice(0, visible).map(s => {
                const c = s.cloneNode(true); c.classList.add("clone"); return c;
            });
            track.prepend(...headClones);
            track.append(...tailClones);

            stepPx       = calcStep();
            currentIndex = visible;
            currentPx    = -currentIndex * stepPx;
            track.style.transition = "none";
            track.style.transform  = `translateX(${currentPx}px)`;
            initialized = true;
        }

        function moveTo(index, animated) {
            currentIndex = index;
            currentPx    = -currentIndex * stepPx;
            track.style.transition = animated ? "transform 0.5s ease" : "none";
            track.style.transform  = `translateX(${currentPx}px)`;
        }

        function slide(dir) {
            if (!initialized || isTransitioning || isDragging) return;
            isTransitioning = true;
            moveTo(currentIndex + dir, true);
            setTimeout(() => {
                if (currentIndex >= total + visible)   moveTo(visible, false);
                else if (currentIndex < visible)       moveTo(total + visible - 1, false);
                setTimeout(() => { isTransitioning = false; }, 50);
            }, 500);
        }

        function getX(e) {
            return e.type.includes("mouse") ? e.pageX : e.touches[0].clientX;
        }

        function dragStart(e) {
            if (!initialized || isTransitioning) return;
            // Let nav button taps through — don't hijack their click
            if (e.target.closest(".testimonial-slider-nav")) return;
            isDragging = true;
            startX = getX(e);
            prevPx = currentPx;
            track.style.transition = "none";
            clearAuto();
            e.preventDefault();
        }

        function dragMove(e) {
            if (!isDragging) return;
            e.preventDefault();
            const diff = getX(e) - startX;
            currentPx = prevPx + diff;
            track.style.transform = `translateX(${currentPx}px)`;
            wrap.style.cursor = Math.abs(diff) > 5 ? "grabbing" : "grab";
        }

        function dragEnd() {
            if (!isDragging) return;
            isDragging = false;
            wrap.style.cursor = "grab";
            track.style.transition = "transform 0.3s ease";

            const diff      = currentPx - prevPx;
            const threshold = stepPx / 4;

            if      (diff >  threshold) slide(-1);
            else if (diff < -threshold) slide(1);
            else {
                currentPx = prevPx;
                track.style.transform = `translateX(${currentPx}px)`;
            }
            startAuto();
        }

        function clearAuto() { if (autoTimer) { clearInterval(autoTimer); autoTimer = null; } }
        function startAuto() {
            clearAuto();
            if (window.innerWidth >= 600)
                autoTimer = setInterval(() => slide(1), 4000);
        }

        wrap.addEventListener("mousedown",  dragStart);
        wrap.addEventListener("mousemove",  dragMove);
        wrap.addEventListener("mouseup",    dragEnd);
        wrap.addEventListener("mouseleave", dragEnd);
        wrap.addEventListener("touchstart", dragStart, { passive: false });
        wrap.addEventListener("touchmove",  dragMove,  { passive: false });
        wrap.addEventListener("touchend",   dragEnd);
        wrap.addEventListener("contextmenu", e => { if (isDragging) e.preventDefault(); });
        wrap.style.cursor = "grab";
        wrap.querySelectorAll("img").forEach(img =>
            img.addEventListener("dragstart", e => e.preventDefault())
        );

        let resizeTimer;
        window.addEventListener("resize", () => {
            clearTimeout(resizeTimer);
            clearAuto();
            resizeTimer = setTimeout(() => { init(); startAuto(); }, 200);
        });

        init();
        startAuto();

        window.moveTestimonialSlider = dir => slide(dir);
    })();


    /* ============================================================
       GALLERY SLIDER
       No infinite loop (bounded 0 → max), px-based translation,
       drag + touch. window.moveGallerySlider(dir) from blade.
    ============================================================ */
    (function () {
        const track = document.getElementById("gallerySlider");
        const wrap  = document.querySelector(".gallery-slider-wrap");
        if (!track || !wrap) return;

        let galleryIndex   = 0;
        let currentPx      = 0;
        let prevPx         = 0;
        let isDragging     = false;
        let startX         = 0;
        window.galleryWasDragged = false;

        function getVisible() {
            if (window.innerWidth >= 1200) return 4;
            if (window.innerWidth >= 768)  return 3;
            return 1;
        }

        function calcStep() {
            const first = track.querySelector(".gallery-slide");
            if (!first) return 0;
            const gap = parseFloat(window.getComputedStyle(track).columnGap) || 0;
            return first.offsetWidth + gap;
        }

        function applyPos(animated) {
            track.style.transition = animated ? "transform 0.45s ease" : "none";
            currentPx = -galleryIndex * calcStep();
            track.style.transform = `translateX(${currentPx}px)`;
        }

        function moveGallerySlider(dir) {
            const total   = track.querySelectorAll(".gallery-slide").length;
            const visible = getVisible();
            if (total <= visible) return;

            galleryIndex = Math.max(0, Math.min(galleryIndex + dir, total - visible));
            applyPos(true);
        }

        function getX(e) {
            return e.type.includes("mouse") ? e.pageX : e.touches[0].clientX;
        }

        function dragStart(e) {
            // Let nav button taps through — don't hijack their click
            if (e.target.closest(".gallery-slider-nav")) return;
            isDragging = true;
            startX = getX(e);
            prevPx = currentPx;
            track.style.transition = "none";
            window.galleryWasDragged = false;
            e.preventDefault();
        }

        function dragMove(e) {
            if (!isDragging) return;
            e.preventDefault();
            const diff = getX(e) - startX;
            currentPx = prevPx + diff;
            track.style.transform = `translateX(${currentPx}px)`;
            if (Math.abs(diff) > 5) {
                wrap.style.cursor        = "grabbing";
                window.galleryWasDragged = true;
            }
        }

        function dragEnd() {
            if (!isDragging) return;
            isDragging = false;
            wrap.style.cursor = "grab";

            const total     = track.querySelectorAll(".gallery-slide").length;
            const visible   = getVisible();
            const step      = calcStep();
            const threshold = step / 4;
            const diff      = currentPx - prevPx;

            if      (diff >  threshold) galleryIndex = Math.max(0, galleryIndex - 1);
            else if (diff < -threshold) galleryIndex = Math.min(total - visible, galleryIndex + 1);

            applyPos(true);
        }

        wrap.addEventListener("mousedown",  dragStart);
        wrap.addEventListener("mousemove",  dragMove);
        wrap.addEventListener("mouseup",    dragEnd);
        wrap.addEventListener("mouseleave", dragEnd);
        wrap.addEventListener("touchstart", dragStart, { passive: false });
        wrap.addEventListener("touchmove",  (e) => { dragMove(e); e.preventDefault(); }, { passive: false });
        wrap.addEventListener("touchend",   dragEnd);
        wrap.addEventListener("contextmenu", e => { if (isDragging) e.preventDefault(); });
        wrap.style.cursor = "grab";
        wrap.querySelectorAll("img").forEach(img =>
            img.addEventListener("dragstart", e => e.preventDefault())
        );

        window.addEventListener("resize", () => {
            galleryIndex = 0;
            applyPos(false);
        });

        window.moveGallerySlider = moveGallerySlider;
    })();


    /* ============================================================
       GALLERY LIGHTBOX
       Opens on card click (unless it was a drag gesture).
    ============================================================ */
    (function () {
        const galleryItems = Array.from(document.querySelectorAll(".gallery-card"));
        const lightbox     = document.getElementById("galleryLightbox");
        const lightboxImg  = document.getElementById("galleryLightboxImg");
        const lightboxTitle = document.getElementById("galleryLightboxTitle");
        const lightboxMeta  = document.getElementById("galleryLightboxMeta");

        if (!galleryItems.length || !lightbox) return;

        let currentIndex = 0;

        function render() {
            const item = galleryItems[currentIndex];
            const img  = item.querySelector("img");
            lightboxImg.src          = img ? img.src : "";
            lightboxTitle.textContent = item.dataset.title || "";
            lightboxMeta.textContent  = item.dataset.breed || "";
        }

        function open(index) {
            currentIndex = index;
            render();
            lightbox.classList.add("active");
            document.body.style.overflow = "hidden";
        }

        function close() {
            lightbox.classList.remove("active");
            document.body.style.overflow = "";
        }

        function navigate(dir) {
            currentIndex = (currentIndex + dir + galleryItems.length) % galleryItems.length;
            render();
        }

        galleryItems.forEach((item, i) => {
            item.addEventListener("click", () => {
                if (window.galleryWasDragged) { window.galleryWasDragged = false; return; }
                open(i);
            });
        });

        window.openGalleryLightbox = open;
        window.closeGalleryLightbox = close;
        window.navigateGallery = navigate;
    })();

});
