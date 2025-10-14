## 2025-10-14

### Fixed

- **Skeleton Loaders CLS Issue**: Fixed Cumulative Layout Shift (CLS) caused by `rand()` in skeleton components
    - Replaced random dimensions with fixed, predictable Tailwind utility classes
    - skeleton-card: Now uses fixed `h-20` instead of `rand(60, 100)px`
    - skeleton-list: Uses cycling array of widths (`w-3/4`, `w-4/5`, `w-5/6`) instead of `rand(60, 90)%`
    - skeleton-table: Uses predictable width arrays for headers and cells instead of `rand()` values
    - Eliminates layout shift on each render, improving Core Web Vitals scores
    - Provides consistent, professional appearance across all page loads
    - Uses Tailwind classes instead of inline styles for better performance
    - Updated documentation with CLS prevention best practices

### Added

- **Design System Modernization to 2025 Standards**: Comprehensive upgrade with cutting-edge visual effects and animations
    - **Advanced Animation System**:
        - Spring-based physics animations using custom cubic-bezier easing functions
        - 30+ new animation utilities (fade, slide, scale, rotate, bounce, flip, stagger)
        - Scroll-triggered animations with Intersection Observer API
        - Parallax effects on background elements
        - View Transitions API support for smooth page navigation
    - **Enhanced Visual Effects**:
        - Multi-layer glassmorphism with backdrop blur and saturation
        - Animated mesh gradients with 3+ color layers
        - Noise texture overlay for organic depth
        - Gradient borders with animation support
        - 3D depth effects with shadows and highlights
    - **Micro-Interactions & Feedback**:
        - Material Design-style ripple effect on all buttons
        - Magnetic cursor effect on primary interactive elements
        - Card tilt effect with 3D transforms on hover
        - Enhanced button hover states with spring animations
        - Form input success/error animations with shake effect
    - **Loading States & Skeletons**:
        - Created reusable skeleton loader components (`x-skeleton-card`, `x-skeleton-list`, `x-skeleton-table`)
        - Shimmer effect for skeleton loaders
        - Loading spinner with spring animations
        - Smooth skeleton hide transitions
        - **Integrated skeleton loaders across the application**:
            - Projects List (Livewire): Shows 3 skeleton cards during search/pagination
            - Dashboard: Skeleton lists for recent projects (3 items) and tickets (4 items)
            - Tickets Index: Skeleton table (8 rows × 10 cols) during page load
            - My Tickets: Skeleton list (5 items) during page load
            - Strategic delay timings (100-200ms) prevent jarring flashes on fast loads
            - Smooth fade-in/fade-out transitions with Alpine.js and Livewire
            - Created comprehensive documentation (`docs/SKELETON_LOADERS.md`)
    - **Typography System Upgrade**:
        - Fluid typography with CSS `clamp()` for responsive scaling (9 fluid size variants)
        - Enhanced heading hierarchy with optimized letter spacing for dark mode
        - Gradient text utilities with animations
        - Text shadow and glow effects
    - **Enhanced Component System**:
        - Buttons: 3D depth, gradient backgrounds, glow effects, loading states, icon animations
        - Cards: Multi-layer glass, hover tilt, scroll-triggered entrance, premium variants
        - Forms: Floating label animations, animated focus states, validation feedback
        - Badges: Pulse animation for urgent states, improved color contrast
    - **Accessibility Enhancements**:
        - `prefers-reduced-motion` support - disables animations for users with motion sensitivity
        - `:focus-visible` instead of `:focus` for better keyboard navigation
        - Skip-to-content link for screen readers
        - ARIA landmarks (banner, main, contentinfo) throughout layout
        - High contrast mode support
        - Keyboard shortcut (Ctrl+/) to jump to main content
    - **Advanced Glassmorphism**:
        - 4 glass variants (glass, glass-dark, glass-frosted, glass-premium)
        - Enhanced backdrop blur scales (xs, sm, md, lg, xl, 2xl, 3xl)
        - Backdrop saturation control (50%, 100%, 150%, 200%)
        - Multi-layer glass effect with highlights and shadows
    - **Scroll & Navigation**:
        - Scroll progress indicator at top of page
        - Smooth scroll with easing and header offset
        - Parallax background effects with customizable speed
        - Enhanced scrollbar with gradient styling
    - **Performance Optimizations**:
        - GPU acceleration with `transform: translateZ(0)` and `will-change`
        - CSS containment for improved paint performance
        - Lazy load animations using Intersection Observer
        - Debounced scroll listeners with `requestAnimationFrame`
        - Optimized for 60fps smooth animations
    - **New Utilities**:
        - Mesh gradient backgrounds (static and animated)
        - Noise texture overlay
        - Glow border animations
        - 3D transform utilities (transform-3d, perspective)
        - Tilt and magnetic effects
        - Scroll snap utilities
- Created `public/noise.svg`: SVG noise texture for organic depth
- Interactive JavaScript behaviors in `resources/js/app.js`:
    - Ripple effect manager
    - Magnetic cursor controller
    - Card tilt effect handler
    - Scroll animation observer
    - Parallax controller
    - Form enhancement manager
- Skeleton loader Blade components for improved UX during async operations

### Changed

- **Tailwind Configuration**: Extended with 100+ new utilities, animations, and easing functions
    - Added fluid typography scales (9 responsive font sizes)
    - Added spring-based easing functions (spring, spring-in, spring-out, bounce)
    - Extended animation keyframes (25+ new animations)
    - Enhanced backdrop blur and saturation scales
    - Expanded box shadow system with glow variants
- **CSS Architecture**: Complete rewrite of `resources/css/app.css` (700+ lines)
    - Modernized button system with 3D depth and spring animations
    - Enhanced card system with tilt and multi-layer glass effects
    - Improved form inputs with floating labels and validation animations
    - Upgraded badge system with pulse animations
    - Enhanced navigation with animated indicators
- **Layout Enhancements**: Updated `resources/views/layouts/app.blade.php`
    - Added skip-to-content link for accessibility
    - Enhanced background effects with parallax support
    - Added proper ARIA landmarks
    - Improved accessibility metadata
- **ESLint Configuration**: Added browser globals (setTimeout, IntersectionObserver, console, etc.)

### Fixed

- CSS class typo: Changed invalid `duration-400` to `duration-300`
- Stylelint: Added quotes to `url()` function in noise texture reference
- Stylelint: Changed `currentColor` to lowercase `currentcolor` for spec compliance

### Technical Details

- Spring easing function: `cubic-bezier(0.34, 1.56, 0.64, 1)` for bouncy animations
- All animations respect `prefers-reduced-motion` accessibility preference
- Noise texture has 0.03 opacity for subtle grain effect
- Parallax elements use data attributes for speed configuration
- Ripple effects auto-cleanup after 600ms
- Skeleton loaders auto-hide 500ms after content loads
- Scroll progress uses `scaleX` for performant animation

- SMTP Email Provider Setup: Configured Brevo (Sendinblue) as the transactional email provider
    - Created comprehensive `docs/EMAIL_SETUP.md` documentation with setup instructions, DNS verification, and troubleshooting
    - Configured DDEV to use Mailpit for local development (port 1025, accessible at http://issue-forge.ddev.site:8025)
    - Updated `config/mail.php` to remove deprecated `scheme` parameter and add `verify_peer` security setting
    - Added Brevo API configuration to `config/services.php` for optional HTTP API fallback
    - Added comprehensive email configuration feature tests covering SMTP settings, notifications, and environment checks
    - Environment variable support for MAIL_EHLO_DOMAIN and MAIL_VERIFY_PEER
    - Implemented custom Brevo API mail transport (`BrevoApiTransport`) to bypass firewall restrictions
    - Installed `getbrevo/brevo-php` SDK for HTTP API integration
    - Registered custom mail transport in `AppServiceProvider` for seamless Laravel Mail integration

### Changed

- Enhanced SMTP mailer configuration with explicit `encryption` parameter and SSL verification
- Mail configuration now properly supports both development (Mailpit) and production (Brevo SMTP/API) environments
- Updated documentation to recommend Brevo API for production (bypasses SMTP port blocking)

### Fixed

- Fixed `ProjectAndTicketDescriptionPlacementTest` to use project slug instead of ID for route resolution

## 2025-09-10

### Added

- API v1 for Projects and Tickets: CRUD endpoints under `/api/v1/*`.
- Admin bearer token auth via `token.admin` middleware and `API_ADMIN_TOKEN`.
- Form Requests for validation, API Resources for responses.
- Feature tests covering basic CRUD and auth scenarios.

## 2025-09-10

- feat: Move project and ticket descriptions out of headers into a dedicated card below the title. Added reusable `x-description-card` component, updated `projects.show` and `tickets.show`, and added feature tests to assert placement.
