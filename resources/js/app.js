import './bootstrap';

// Alpine.js is bundled with Livewire 3, no need to import it separately

// ============================================
// EasyMDE Markdown Editor (SimpleMDE maintained fork)
// ============================================
import EasyMDE from 'easymde';
window.SimpleMDE = EasyMDE; // Keep SimpleMDE name for backward compatibility
window.EasyMDE = EasyMDE;

// ============================================
// Modern Design System Enhancements
// ============================================

/**
 * Ripple Effect on Buttons
 * Creates a Material Design-style ripple on click
 */
function createRipple(event) {
    const button = event.currentTarget;

    // Only apply to elements with ripple-container class
    if (!button.classList.contains('ripple-container') && !button.classList.contains('btn')) {
        return;
    }

    const circle = document.createElement('span');
    const diameter = Math.max(button.clientWidth, button.clientHeight);
    const radius = diameter / 2;

    const rect = button.getBoundingClientRect();
    circle.style.width = circle.style.height = `${diameter}px`;
    circle.style.left = `${event.clientX - rect.left - radius}px`;
    circle.style.top = `${event.clientY - rect.top - radius}px`;
    circle.classList.add('ripple');

    const ripple = button.getElementsByClassName('ripple')[0];

    if (ripple) {
        ripple.remove();
    }

    button.appendChild(circle);

    // Remove ripple after animation
    setTimeout(() => circle.remove(), 600);
}

// Apply ripple effect to all buttons
document.addEventListener('click', createRipple);

/**
 * Magnetic Cursor Effect
 * Buttons follow the cursor when hovering nearby
 * Uses event delegation to support dynamically added elements
 */
let currentMagneticElement = null;

document.addEventListener(
    'mouseover',
    (e) => {
        const magneticElement = e.target.closest('.magnetic, .btn-primary, .btn-accent');
        if (magneticElement && magneticElement !== currentMagneticElement) {
            currentMagneticElement = magneticElement;
        }
    },
    true,
);

document.addEventListener('mousemove', (e) => {
    if (currentMagneticElement && currentMagneticElement.contains(e.target)) {
        const rect = currentMagneticElement.getBoundingClientRect();
        const centerX = rect.left + rect.width / 2;
        const centerY = rect.top + rect.height / 2;

        const deltaX = (e.clientX - centerX) * 0.2;
        const deltaY = (e.clientY - centerY) * 0.2;

        currentMagneticElement.style.transform = `translate(${deltaX}px, ${deltaY}px)`;
    }
});

document.addEventListener(
    'mouseout',
    (e) => {
        const magneticElement = e.target.closest('.magnetic, .btn-primary, .btn-accent');
        if (magneticElement && !magneticElement.contains(e.relatedTarget)) {
            magneticElement.style.transform = 'translate(0, 0)';
            if (currentMagneticElement === magneticElement) {
                currentMagneticElement = null;
            }
        }
    },
    true,
);

/**
 * Card Tilt Effect
 * 3D tilt effect on card hover
 * Uses event delegation to support dynamically added elements and prevent duplicate listeners
 */
let currentTiltCard = null;

document.addEventListener(
    'mouseover',
    (e) => {
        const tiltCard = e.target.closest('.tilt, .card-hover');
        if (tiltCard && tiltCard !== currentTiltCard) {
            currentTiltCard = tiltCard;
        }
    },
    true,
);

document.addEventListener('mousemove', (e) => {
    if (currentTiltCard && currentTiltCard.contains(e.target)) {
        const rect = currentTiltCard.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        const rotateX = (y - centerY) / 10;
        const rotateY = (centerX - x) / 10;

        currentTiltCard.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
    }
});

document.addEventListener(
    'mouseout',
    (e) => {
        const tiltCard = e.target.closest('.tilt, .card-hover');
        if (tiltCard && !tiltCard.contains(e.relatedTarget)) {
            tiltCard.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
            if (currentTiltCard === tiltCard) {
                currentTiltCard = null;
            }
        }
    },
    true,
);

/**
 * Scroll-Triggered Animations
 * Fade in elements as they enter viewport
 */
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px',
};

const animateOnScroll = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-fade-in-up');
            animateOnScroll.unobserve(entry.target);
        }
    });
}, observerOptions);

// Observe elements with data-animate attribute
document.querySelectorAll('[data-animate]').forEach((el) => {
    animateOnScroll.observe(el);
});

// Auto-observe cards and sections
document.querySelectorAll('.card:not(.no-animate), section:not(.no-animate)').forEach((el) => {
    // Only if not already animating
    if (!el.classList.contains('animate-fade-in-up') && !el.classList.contains('animate-fade-in')) {
        animateOnScroll.observe(el);
    }
});

/**
 * Scroll Progress Indicator
 * Shows reading progress at top of page
 */
function updateScrollProgress() {
    const scrollProgress = document.querySelector('.scroll-progress');
    if (!scrollProgress) {
        // Create scroll progress element if it doesn't exist
        const progressBar = document.createElement('div');
        progressBar.className = 'scroll-progress';
        document.body.appendChild(progressBar);
    }

    const winScroll = document.documentElement.scrollTop;
    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const scrolled = winScroll / height;

    const progressBar = document.querySelector('.scroll-progress');
    if (progressBar) {
        progressBar.style.transform = `scaleX(${scrolled})`;
    }
}

// Debounce scroll handler for performance
let scrollTimeout;
window.addEventListener(
    'scroll',
    () => {
        if (scrollTimeout) {
            window.cancelAnimationFrame(scrollTimeout);
        }
        scrollTimeout = window.requestAnimationFrame(() => {
            updateScrollProgress();
        });
    },
    { passive: true },
);

/**
 * Parallax Background Effect
 * Subtle parallax on background gradients
 */
function initParallax() {
    const parallaxElements = document.querySelectorAll('.parallax, [data-parallax]');

    window.addEventListener(
        'scroll',
        () => {
            const scrolled = window.pageYOffset;

            parallaxElements.forEach((el) => {
                const speed = el.dataset.parallaxSpeed || 0.5;
                const yPos = -(scrolled * speed);
                el.style.transform = `translate3d(0, ${yPos}px, 0)`;
            });
        },
        { passive: true },
    );
}

initParallax();

/**
 * Stagger Animation for Lists
 * Animate list items with cascading delay
 */
function staggerListItems() {
    const lists = document.querySelectorAll('[data-stagger]');

    lists.forEach((list) => {
        const items = list.children;
        Array.from(items).forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
            item.classList.add('animate-fade-in-up');
        });
    });
}

staggerListItems();

/**
 * Enhanced Focus Management (Accessibility)
 * Trap focus within modals and improve keyboard navigation
 */
document.addEventListener('keydown', (e) => {
    // Skip to main content with keyboard shortcut
    if (e.key === '/' && e.ctrlKey) {
        e.preventDefault();
        const main = document.querySelector('main');
        if (main) {
            main.focus();
            main.scrollIntoView({ behavior: 'smooth' });
        }
    }
});

/**
 * Form Input Enhancements
 * Add success/error animations
 */
function enhanceFormInputs() {
    const inputs = document.querySelectorAll('.input');

    inputs.forEach((input) => {
        // Add success animation on valid input
        input.addEventListener('blur', () => {
            if (input.validity.valid && input.value) {
                input.classList.add('border-success-500');
                input.classList.remove('border-danger-500');
                setTimeout(() => {
                    input.classList.remove('border-success-500');
                }, 2000);
            }
        });

        // Shake on invalid input
        input.addEventListener('invalid', () => {
            input.classList.add('animate-shake');
            setTimeout(() => {
                input.classList.remove('animate-shake');
            }, 500);
        });
    });
}

enhanceFormInputs();

/**
 * Skeleton Loader Management
 * Automatically hide skeletons when content loads
 */
function hideSkeletons() {
    const skeletons = document.querySelectorAll('.skeleton-card, .skeleton-box');
    skeletons.forEach((skeleton) => {
        skeleton.classList.add('animate-fade-out');
        setTimeout(() => {
            skeleton.style.display = 'none';
        }, 300);
    });
}

// Call after content is loaded
window.addEventListener('load', () => {
    setTimeout(hideSkeletons, 500);
});

/**
 * View Transitions API Support
 * Smooth page transitions for modern browsers
 */
if (document.startViewTransition) {
    // Intercept navigation for smooth transitions
    window.addEventListener('beforeunload', () => {
        document.documentElement.classList.add('is-transitioning');
    });
}

/**
 * Performance: Lazy Load Animations
 * Only apply expensive animations to visible elements
 */
const lazyAnimationObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animation-ready');
            }
        });
    },
    { rootMargin: '50px' },
);

document.querySelectorAll('.card-glow, .hover-lift').forEach((el) => {
    lazyAnimationObserver.observe(el);
});

/**
 * Smooth Scroll with Offset
 * Account for fixed headers
 */
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href === '#') return;

        e.preventDefault();
        const target = document.querySelector(href);

        if (target) {
            const offset = 80; // Height of fixed header
            const elementPosition = target.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - offset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth',
            });
        }
    });
});

/**
 * Console Art (Easter Egg)
 * Display styled console message
 */
console.log(
    '%c🚀 IssueForge %c- Modern Design System 2025',
    'background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); color: white; padding: 8px 16px; border-radius: 8px 0 0 8px; font-size: 14px; font-weight: bold;',
    'background: #0f172a; color: #94a3b8; padding: 8px 16px; border-radius: 0 8px 8px 0; font-size: 14px;',
);

console.log(
    '%cDesigned with ❤️ and cutting-edge web technologies',
    'color: #94a3b8; font-size: 12px; font-style: italic; margin-top: 4px;',
);

/**
 * Export functions for use in other modules
 * Note: Magnetic cursor and tilt effects use event delegation, no initialization needed
 */
window.DesignSystem = {
    createRipple,
    staggerListItems,
    enhanceFormInputs,
    hideSkeletons,
};
