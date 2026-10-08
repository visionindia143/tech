const carouselDuplicates = 3;

function lerp(a, b, t) {
    return a + (b - a) * t;
}

function getTouchMidpoint(touches) {
    let midpoint = {
        x: touches[0].clientX,
        y: touches[0].clientY,
    };

    for (let i = 1; i < touches.length; i++) {
        midpoint.x = lerp(midpoint.x, touches[i].clientX, 0.5);
        midpoint.y = lerp(midpoint.y, touches[i].clientY, 0.5);
    }

    return midpoint;
}

document.addEventListener("DOMContentLoaded", () => {
    const carousels = document.querySelectorAll(".carousel");
    if (carousels.length === 0) return; // Exit if no carousels exist

    carousels.forEach((carousel) => {
        const carouselContent = carousel.querySelector(".carousel-content");
        if (!carouselContent) return; // Skip if carousel content doesn't exist

        initializeCarousel(carousel, carouselContent);
    });
    // Floating rings inject
    const ccwBg = document.getElementById("ccwBg");
    if (ccwBg) {
        const rings = [
            { size: 22, left: 8, delay: 0, dur: 7 },
            { size: 14, left: 18, delay: 1.8, dur: 9 },
            { size: 36, left: 30, delay: 3, dur: 11 },
            { size: 18, left: 42, delay: 0.8, dur: 8 },
            { size: 28, left: 55, delay: 2.2, dur: 10 },
            { size: 12, left: 65, delay: 4, dur: 7 },
            { size: 44, left: 75, delay: 1, dur: 13 },
            { size: 20, left: 85, delay: 3.5, dur: 9 },
            { size: 16, left: 92, delay: 5, dur: 8 },
            { size: 32, left: 22, delay: 6, dur: 12 },
        ];
        rings.forEach((r) => {
            const el = document.createElement("div");
            el.className = "ccw-ring";
            el.style.cssText = `width:${r.size}px;height:${r.size}px;left:${r.left}%;bottom:-${r.size + 8}px;animation-duration:${r.dur}s;animation-delay:${r.delay}s`;
            ccwBg.appendChild(el);
        });
    }
});

function initializeCarousel(carousel, carouselContent) {
    const prefersReducedMotion = matchMedia("(prefers-reduced-motion: reduce)");
    const hasFinePointer = matchMedia("(pointer: fine)");
    let carouselHasMouse = false;
    let carouselTouches = 0;
    let lastMouseX = null;
    let lastTouchX = null;
   

    // An event handler for handling touchend and touchcancel.
    const handleTouchRemove = (event) => {
        carouselTouches -= event.changedTouches.length;
        if (carouselTouches <= 0 && !carouselHasMouse) {
            lastTouchX = null;
        }
    };

  

    // Handle mouse input.
    carousel.addEventListener("mousedown", (event) => {
        carouselHasMouse = true;
    });

    window.addEventListener("mouseup", (event) => {
        carouselHasMouse = false;
        lastMouseX = null;
    });


    carousel.addEventListener("wheel", (event) => {
        if (hasFinePointer.matches && event.shiftKey) {
            event.preventDefault();

            const scrollMultiplier = prefersReducedMotion.matches ? 2 : 0.4;
            scrollDelta += event.deltaY * scrollMultiplier;
        }
    });

    // Handle touch input.
    carousel.addEventListener("touchstart", (event) => {
        if (lastTouchX === null) {
            lastTouchX = getTouchMidpoint(event.touches).x;
        }
        carouselTouches += event.changedTouches.length;
    });

    window.addEventListener("touchmove", (event) => {
        if (lastTouchX !== null) {
            const touchMidpoint = getTouchMidpoint(event.touches);
            scrollDelta = -(touchMidpoint.x - lastTouchX);
            lastTouchX = touchMidpoint.x;
        }
    });

    window.addEventListener("touchend", handleTouchRemove);
    window.addEventListener("touchcancel", handleTouchRemove);

    // This is where the infinite scrolling logic comes to play.
    carousel.addEventListener("scroll", () => {
        const scrollWidth = carouselContent.scrollWidth;

        if (carousel.scrollLeft <= 0) {
            carousel.scrollLeft += scrollWidth;
        } else if (
            carousel.scrollLeft >=
            scrollWidth * (carouselDuplicates + 1)
        ) {
            carousel.scrollLeft -= scrollWidth;
        }
    });

    // Duplicate the carouselContent on both the front and the back of the carousel to create the illusion.
    for (let i = 0; i < carouselDuplicates; i++) {
        const carouselDuplicate = carouselContent.cloneNode(true);
        // For accessibility reasons...
        carouselDuplicate.ariaHidden = true;
        carouselDuplicate.querySelectorAll("a").forEach((element) => {
            element.tabIndex = "-1";
        });

        carousel.prepend(carouselDuplicate);
        carousel.append(carouselDuplicate.cloneNode(true));
    }

    // Shift the starting scroll position to the right.
    // Use setTimeout to ensure DOM is fully rendered
    setTimeout(() => {
        carousel.scrollLeft += carouselContent.offsetWidth * carouselDuplicates;
    }, 100);

  
// 🔥 AUTO SCROLL ADD
let autoSpeed = 0.8;

function autoScroll() {
    if (!carouselHasMouse && carouselTouches === 0) {
        carousel.scrollLeft += autoSpeed;
    }
    requestAnimationFrame(autoScroll);
}

autoScroll();
window.addEventListener("mousemove", (event) => {
    if (carouselHasMouse) {
        if (lastMouseX !== null) {
            const move = lastMouseX - event.clientX;
            carousel.scrollLeft += move * 2; // 🔥 smooth fast drag
        }
        lastMouseX = event.clientX;
    }
});
window.addEventListener("touchmove", (event) => {
    if (lastTouchX !== null) {
        const touchMidpoint = getTouchMidpoint(event.touches);
        const move = lastTouchX - touchMidpoint.x;
        carousel.scrollLeft += move * 2; // 🔥 smooth mobile
        lastTouchX = touchMidpoint.x;
    }
});
}
