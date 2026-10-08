/* =====================================================
   custom.js  —  Non-slider page interactions
   Sections: FAQ ACCORDION | PET PARTICLES | CTA BUBBLES
   Sliders have been moved to allSlider.js
   ===================================================== */


/* ================= FAQ ACCORDION ================= */
window.toggleFaq = function (button) {
    const faqItem = button.parentElement;
    const answer  = faqItem.querySelector(".faq-answer");
    const icon    = button.querySelector("i");

    document.querySelectorAll(".faq-item").forEach(item => {
        if (item !== faqItem) {
            item.querySelector(".faq-answer").style.maxHeight = "0";
            item.querySelector(".faq-question").classList.remove("active");
            item.querySelector(".faq-question i").classList.remove("rotated");
        }
    });

    if (!answer.style.maxHeight || answer.style.maxHeight === "0px") {
        answer.style.maxHeight = answer.scrollHeight + "px";
        button.classList.add("active");
        icon.classList.add("rotated");
    } else {
        answer.style.maxHeight = "0";
        button.classList.remove("active");
        icon.classList.remove("rotated");
    }
};


/* ================= PET PARTICLES ================= */
(function () {
    const container = document.getElementById("petParticles");
    if (!container) return;

    const icons = [
        '<i class="fas fa-paw"></i>',
        '<i class="fas fa-heart"></i>',
        '<i class="fas fa-bone"></i>',
        '<i class="fas fa-star"></i>',
        '<i class="fas fa-dog"></i>',
        '<i class="fas fa-cat"></i>',
    ];
    const colors = [
        "var(--clr-primary)", "var(--clr-accent)",
        "#ff6b8a", "#ffd166", "#a8dadc", "#b5e48c",
    ];
    const swayClasses = ["", "sway-left", "sway-right"];

    function createParticle() {
        const p = document.createElement("span");
        p.className = "pet-particle " + swayClasses[Math.floor(Math.random() * 3)];
        p.innerHTML = icons[Math.floor(Math.random() * icons.length)];
        p.style.left      = Math.random() * 100 + "vw";
        p.style.fontSize  = (Math.random() * 20 + 18) + "px";
        p.style.color     = colors[Math.floor(Math.random() * colors.length)];
        const dur  = Math.random() * 12 + 10;
        const sway = Math.random() * 3 + 3;
        p.style.animationDuration = dur + "s, " + sway + "s";
        p.style.animationDelay    = Math.random() * 5 + "s";
        container.appendChild(p);
        setTimeout(() => p.remove(), (dur + 5) * 1000);
    }

    for (let i = 0; i < 12; i++) setTimeout(createParticle, i * 400);
    setInterval(createParticle, 2500);
})();


/* ================= CTA BUBBLES ================= */
document.addEventListener("DOMContentLoaded", () => {
    const wrap = document.getElementById("ctaBubbles");
    if (!wrap) return;

    const configs = [
        { size: 18, left: 8,  delay: 0,   dur: 7    },
        { size: 28, left: 18, delay: 1.5, dur: 9    },
        { size: 12, left: 28, delay: 3,   dur: 6.5  },
        { size: 36, left: 38, delay: 0.8, dur: 11   },
        { size: 22, left: 48, delay: 2.2, dur: 8    },
        { size: 14, left: 55, delay: 4,   dur: 7.5  },
        { size: 30, left: 63, delay: 1,   dur: 10   },
        { size: 16, left: 72, delay: 3.5, dur: 6    },
        { size: 40, left: 80, delay: 0.5, dur: 12   },
        { size: 20, left: 88, delay: 2.8, dur: 8.5  },
        { size: 10, left: 93, delay: 5,   dur: 7    },
        { size: 26, left: 12, delay: 6,   dur: 9.5  },
        { size: 34, left: 44, delay: 4.5, dur: 11.5 },
        { size: 15, left: 70, delay: 6.5, dur: 8    },
        { size: 24, left: 25, delay: 7,   dur: 10   },
    ];

    configs.forEach(c => {
        const el = document.createElement("div");
        el.className = "bubble";
        el.style.cssText = `width:${c.size}px;height:${c.size}px;left:${c.left}%;bottom:-${c.size + 10}px;animation-duration:${c.dur}s;animation-delay:${c.delay}s`;
        wrap.appendChild(el);
    });
});


// service card clickable

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".service-card").forEach(card => {

        card.addEventListener("click", function (e) {

            // agar user already link pe click kar raha hai to double redirect na ho
            if (e.target.closest("a")) return;

            const url = this.getAttribute("data-url");
            if (url) {
                window.location.href = url;
            }
        });

    });
});
