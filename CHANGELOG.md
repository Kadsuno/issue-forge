## 2025-10-16

### Changed

- **README Roadmap**: Updated roadmap to reflect current implementation status
    - Marked "Ticket comments & discussions" as completed
    - Marked "Email notifications (Brevo SMTP/API)" as completed
    - Added clarifying notes to "Extended workflow states" roadmap item (blocked, needs review, reopened, status history)
    - Reordered roadmap items to match implementation priority

## 2025-10-15 (Part 3) - UX, Accessibility, Type Safety & Testing

### Added

- **Global Livewire Loading Indicator**: Added animated progress bar at page top
    - Shows gradient animation during Livewire requests
    - Uses `wire:loading.delay.longer` to avoid flashing on fast requests
    - Provides visual feedback for async operations
- **Button Loading States**: Added spinner indicators to interactive buttons
    - Toggle status buttons show loading spinner during state change
    - "Create First Project" button disables with spinner during action
    - Prevents duplicate requests with disabled state
- **ARIA Labels**: Enhanced accessibility for screen readers
    - Added descriptive aria-label to search inputs
    - Added context-aware labels to toggle buttons (includes project name)
    - Marked decorative SVG icons with aria-hidden
- **Unit Tests for Models**: Created comprehensive test coverage
    - tests/Unit/Models/ProjectTest.php: 15 tests for Project model
    - tests/Unit/Models/TicketTest.php: 18 tests for Ticket model
    - tests/Unit/Models/UserTest.php: 12 tests for User model
    - Tests cover slug generation, relationships, type casts, fillable attributes
- **EasyMDE Bundled**: Replaced CDN-loaded SimpleMDE with npm-bundled EasyMDE
    - Installed EasyMDE (maintained SimpleMDE fork) via npm
    - Bundled via Vite for better caching and offline support
    - Exposed as both `window.SimpleMDE` and `window.EasyMDE` for compatibility
    - CSS imported in app.css for consistent loading
- **Comprehensive Type Hints**: Added strict typing to main controllers
    - Added `declare(strict_types=1)` to ProjectController and TicketController
    - All 14 controller methods now have explicit return types
    - Made controllers final classes to prevent inheritance
    - Enhanced IDE support and type safety

### Changed

- **Font Loading**: Fixed Google Fonts duplicate loading across layouts
    - Removed font links from `app.blade.php` and `guest.blade.php`
    - Consolidated to single CSS import in `app.css`
    - Improves caching and reduces redundant requests
- **SimpleMDE/EasyMDE**: Migrated from CDN to npm bundle
    - Removed `https://cdn.jsdelivr.net/simplemde` script/link tags
    - All assets now served locally through Vite
    - No external dependencies at runtime

### Fixed

- **Alpine.js Duplicate**: Removed manual Alpine.js import (Livewire bundles it)
- **SimpleMDE Dependencies**: Switched to EasyMDE to avoid CodeMirror resolution issues
- **Tailwind CSS Version Conflict**: Removed @tailwindcss/vite v4 plugin
    - Eliminated version mismatch between Tailwind v3 and v4 plugin
    - Reduced dependencies by 17 packages
    - Maintains standard PostCSS integration for Tailwind v3

### Improved

- **User Experience**: Loading states provide clear feedback during async operations
- **Accessibility**: Better screen reader support with ARIA labels
- **Performance**: Fonts and editor assets now cached locally
- **Testing**: Foundation for comprehensive test coverage across models
- **Type Safety**: Strict typing prevents runtime errors and improves maintainability
- **Code Quality**: All code passes Pint, PHPUnit, ESLint, and Prettier checks

---

## 2025-10-15 (Part 2) - Comprehensive Security & Code Quality Improvements

### Added

- **HasSlug Trait**: Created reusable trait for automatic slug generation with uniqueness checks
    - Replaces duplicate slug generation code across Project, Ticket, and User models
    - Implements efficient algorithm to prevent N+1 queries
    - Configurable via `getSlugSourceColumn()` and `getDefaultSlugBase()` methods
- **Security Headers Middleware**: Added HTTP security headers to all web responses
    - X-Frame-Options: SAMEORIGIN (prevent clickjacking)
    - X-Content-Type-Options: nosniff (prevent MIME sniffing)
    - Referrer-Policy: strict-origin-when-cross-origin
    - Permissions-Policy: restrict geolocation, microphone, camera
    - X-XSS-Protection: enabled for legacy browsers
- **Authorization Policies**: Implemented Laravel Policies for Project and Ticket models
    - ProjectPolicy: controls view, create, update, delete, archive actions
    - TicketPolicy: controls view, create, update, delete, assign, comment actions
    - Admins have full access, regular users have context-based permissions
- **Rate Limiting**: Added throttling to sensitive authentication routes
    - Login: 5 attempts per minute per IP
    - Registration: 10 attempts per minute per IP
    - Password reset request: 3 attempts per minute per IP
    - Password reset: 5 attempts per minute per IP
    - Password confirmation: 10 attempts per minute per IP
- **.env.example**: Created comprehensive environment variable template
    - Documented all required configuration variables
    - Includes inline comments for each setting
    - Covers app, database, mail, cache, queue, API, and service configurations

### Changed

- **API Authorization**: Implemented proper authorization in API FormRequests
    - ProjectStoreRequest, ProjectUpdateRequest: check create/update policies
    - TicketStoreRequest, TicketUpdateRequest: check create/update policies
    - Supports both user-based (policies) and token-based (admin) authentication
- **Project Slug Generation**: Fixed missing uniqueness check in Project model
    - Now generates unique slugs with suffix incrementing (e.g., project-1, project-2)
    - Added proper type hints to boot() method
- **Alpine.js Loading**: Removed duplicate Alpine.js loading
    - Removed manual Alpine import from `resources/js/app.js`
    - Removed alpinejs npm dependency (Livewire 3 bundles it)
    - Fixes console warning about multiple Alpine instances
- **Floating Action Button**: Made visible on mobile devices
    - Changed from `hidden lg:block` to responsive visibility
    - Adjusted positioning: `bottom-4 right-4` on mobile, `bottom-6 right-6` on large screens
- **API Route Model Binding**: Fixed tickets API to use ID-based route binding
    - Added explicit `ticket:id` parameter binding for API routes
    - Updated TicketsController::update() to use route model binding
- **Code Formatting**: Applied Laravel Pint fixes to all PHP files
    - Fixed 5 style issues across new files
    - Ensured PSR-12 compliance

### Fixed

- **Security**: Closed authorization gap in API endpoints
    - Previously all API requests were authorized without checking permissions
    - Now properly validates user permissions via Laravel Policies
- **Security**: Protected authentication routes from brute force attacks
    - Added rate limiting to prevent credential stuffing and DoS attacks
- **Bug**: Project slug collisions
    - Projects with identical names now get unique slugs with numeric suffixes
- **Bug**: Test failures after authorization implementation
    - Fixed API tests to work with new authorization layer
    - Updated FormRequests to handle token-based authentication

### Improved

- **Code Quality**: Reduced code duplication with HasSlug trait
    - Eliminated 60+ lines of duplicate slug generation code
    - Single source of truth for slug generation logic
- **Code Quality**: Added type hints to model boot methods
    - Project, Ticket, User models now have proper type declarations
- **Code Quality**: ProjectList Livewire component already uses eager loading
    - Verified no N+1 query issues in project listings
- **Security**: Defense in depth with multiple security layers
    - HTTP security headers protect against common web vulnerabilities
    - Rate limiting prevents abuse and automated attacks
    - Authorization policies ensure proper access control
    - Token authentication secures API endpoints

### Testing

- All 69 existing tests passing
- No new linter errors
- JavaScript/CSS linting clean (only DDEV config warnings)

---

## 2025-10-15 (Part 1) - Notification Improvements

### Changed

- **Compact Notifications**: Made notifications more compact and scannable
    - **TicketUpdated Notification**: Changed to show `changes_count` instead of detailed changes array
        - Replaced detailed field changes with simple count (e.g., "Updated 3 fields")
        - Messages truncated to 120 characters maximum to prevent overflow
        - Removed `changes` array from notification data for cleaner payloads
    - **Notification Dropdown UI**: Enhanced notification display in dropdown
        - Changes now show as compact count instead of field-by-field details
        - Added `line-clamp-1` to ticket titles to prevent wrapping
        - Snippet display kept at `line-clamp-2` for readability
    - **Toast Notifications**: Updated popup toast notifications
        - Toast title truncated to 80 characters with ellipsis
        - Changes displayed as simple count instead of detailed list
        - Added line clamps: title (1 line), body (2 lines), meta (1 line)
        - Improved visual consistency and readability
    - **Email Notifications**: Updated email templates for compactness
        - Changed `ticket_updated.blade.php` to show field count instead of detailed changes
        - Message truncated to 120 characters in emails
        - Ticket descriptions truncated to 200 characters in both email templates
        - Comment body truncated to 200 characters in `ticket_commented.blade.php`
        - Maintains professional appearance while reducing email length
    - **Testing**: Added comprehensive test suite for notification compaction
        - Tests for `changes_count` field presence and accuracy
        - Tests for message truncation at 120 characters
        - Tests for all required notification data fields
        - Tests for zero changes case
        - Tests for short message preservation
        - Tests for email notification truncation

## 2025-10-14

### Fixed

- **Interactive Effects with Dynamic Content**: Fixed inconsistent behavior of interactive effects with dynamically loaded content
    - **Magnetic Cursor Effect**: Now uses event delegation to automatically work with Livewire/dynamic content
    - **Card Tilt Effect**: Refactored to use event delegation, eliminating duplicate event listeners
    - Prevents memory leaks from accumulating event listeners on Livewire navigation
    - Both effects now track current hovered element to ensure proper cleanup
    - Uses `mouseover`/`mouseout` with proper `relatedTarget` checking for accurate hover detection
    - No re-initialization needed - effects work immediately on all dynamically added elements
    - Removed `initTiltEffect()` function and Livewire navigation listener (no longer needed)
- **Tailwind Utility Registration with Nested Selectors**: Fixed utilities with nested selectors failing to register
    - Separated utilities with nested selectors (`&::before`, `&:focus-visible`) into `addComponents()`
    - `addUtilities()` now only contains simple utilities without nesting
    - Affected utilities: `.noise-texture`, `.focus-ring`, `.focus-ring-accent`
    - Fixed syntax prevents build errors when these utilities are used in templates
    - Tailwind now correctly processes pseudo-elements and pseudo-classes in these utilities
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
