# Skeleton Loaders Implementation

## Overview

Skeleton loaders have been integrated throughout the application to provide visual feedback during content loading, significantly improving perceived performance and user experience.

## Components Created

### 1. `<x-skeleton-card>`

**Location:** `resources/views/components/skeleton-card.blade.php`

A skeleton loader for card layouts with header and content sections.

**Usage:**

```blade
<x-skeleton-card :rows="3" />
```

**Parameters:**

- `rows` (optional): Number of content rows to display (default: 3)

**Best for:**

- Project cards
- Dashboard summary cards
- Content preview cards

---

### 2. `<x-skeleton-list>`

**Location:** `resources/views/components/skeleton-list.blade.php`

A skeleton loader for list items with avatar and text.

**Usage:**

```blade
<x-skeleton-list :items="5" />
```

**Parameters:**

- `items` (optional): Number of list items to display (default: 4)

**Best for:**

- Recent activity lists
- Project lists
- Ticket lists
- User lists

---

### 3. `<x-skeleton-table>`

**Location:** `resources/views/components/skeleton-table.blade.php`

A skeleton loader for table layouts with headers and rows.

**Usage:**

```blade
<x-skeleton-table :rows="8" :cols="10" />
```

**Parameters:**

- `rows` (optional): Number of table rows (default: 5)
- `cols` (optional): Number of table columns (default: 3)

**Best for:**

- Ticket tables
- Data tables
- Reports

---

## Integration Points

### 1. Projects List (Livewire Component)

**File:** `resources/views/livewire/project-list.blade.php`

**Implementation:**

- Shows 3 skeleton cards during search, pagination, or initial load
- Uses Livewire's `wire:loading` directive
- Automatically hides when data is ready

**Triggers:**

- Search input changes
- Page navigation (next/previous)
- Initial component mount

---

### 2. Dashboard - Recent Projects & Tickets

**File:** `resources/views/dashboard.blade.php`

**Implementation:**

- Uses Alpine.js for loading state management
- Shows skeleton lists for both recent projects and recent tickets
- 100ms delay to prevent flash for fast loads
- Smooth fade transitions

**Components used:**

- `<x-skeleton-list :items="3" />` for recent projects
- `<x-skeleton-list :items="4" />` for recent tickets

---

### 3. Tickets Index (Project Tickets Table)

**File:** `resources/views/tickets/index.blade.php`

**Implementation:**

- Shows skeleton table during page load
- 200ms delay for smooth transition
- Fades out gracefully when content loads

**Component used:**

- `<x-skeleton-table :rows="8" :cols="10" />` matching the actual table structure

---

### 4. My Tickets Page

**File:** `resources/views/tickets/mine.blade.php`

**Implementation:**

- Shows skeleton list during page load
- 150ms delay for optimal UX
- Smooth fade transitions

**Component used:**

- `<x-skeleton-list :items="5" />` for ticket list

---

## Loading State Patterns

### Pattern 1: Livewire Loading States

Used for dynamic, interactive components.

```blade
<!-- Skeleton (shows during loading) -->
<div wire:loading wire:target="search,gotoPage">
    <x-skeleton-card :rows="3" />
</div>

<!-- Content (shows when ready) -->
<div wire:loading.remove wire:target="search,gotoPage">
    <!-- actual content -->
</div>
```

**When to use:**

- Livewire components
- Search inputs
- Pagination
- Filters

---

### Pattern 2: Alpine.js Initial Load

Used for static pages with initial data loading.

```blade
<div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 150)">
    <!-- Skeleton -->
    <div x-show="loading" x-transition:leave="animate-fade-out">
        <x-skeleton-list :items="5" />
    </div>

    <!-- Content -->
    <div x-show="!loading" x-transition:enter="animate-fade-in-up">
        <!-- actual content -->
    </div>
</div>
```

**When to use:**

- Initial page loads
- Heavy data queries
- Static content pages

---

## Animation Classes Used

### Entry Animations

- `animate-fade-in-up` - Fade in with upward motion
- `x-transition:enter` - Alpine.js entry transition

### Exit Animations

- `animate-fade-out` - Smooth fade out
- `x-transition:leave` - Alpine.js exit transition

---

## Styling Features

### Shimmer Effect

All skeleton loaders include an animated shimmer effect using CSS:

```css
.skeleton {
    background: linear-gradient(
        90deg,
        rgba(51, 65, 85, 0.4) 0%,
        rgba(71, 85, 105, 0.4) 50%,
        rgba(51, 65, 85, 0.4) 100%
    );
    background-size: 200% 100%;
    animation: shimmer 2s linear infinite;
}
```

### Responsive Design

- All skeleton loaders are fully responsive
- Match the actual content dimensions
- Adapt to different screen sizes

---

## Performance Considerations

### Delay Timings

- **100ms** - Very fast loads (dashboard sections)
- **150ms** - Standard loads (lists, simple content)
- **200ms** - Slower loads (tables, complex content)

These delays prevent jarring flashes for fast-loading content while still providing feedback for slower loads.

### GPU Acceleration

Skeleton animations use `transform` and `opacity` for smooth 60fps performance:

```css
animation: shimmer 2s linear infinite;
will-change: background-position;
```

---

## Best Practices

### Do's ✅

- Match skeleton layout to actual content
- Use appropriate delay timings
- Combine with smooth transitions
- Test on slow connections
- Keep skeleton simple and minimal
- **Use fixed, predictable dimensions** - Prevents Cumulative Layout Shift (CLS)
- Use Tailwind utility classes for dimensions (e.g., `w-20`, `h-8`)
- Create predictable variations using arrays instead of random values

### Don'ts ❌

- Don't use too many skeleton items
- Don't make delay too long (>300ms)
- Don't use for instant content
- Don't mix multiple loading patterns
- Don't forget `x-cloak` for Alpine.js
- **❌ NEVER use `rand()` for dimensions** - Causes layout shift and poor UX

---

## Future Enhancements

### Potential Additions

1. **Skeleton for Forms** - Create form-specific skeleton loader
2. **Skeleton for Comments** - Add comment thread skeleton
3. **Progressive Loading** - Implement progressive content reveal
4. **Network-Aware** - Adjust delays based on connection speed
5. **Accessibility** - Add ARIA live regions for screen readers

---

## Layout Stability & CLS Prevention

### The Problem with Random Dimensions

Initially, skeleton components used `rand()` to generate random dimensions:

```php
❌ BAD - Causes CLS:
<div style="width: {{ rand(80, 150) }}px;">...</div>
```

**Issues:**

- Each render produces different dimensions
- Causes Cumulative Layout Shift (CLS) - a Core Web Vital
- Unpredictable user experience
- Hurts SEO and performance scores

### The Solution: Predictable Variations

Use fixed dimensions with predictable patterns:

```php
✅ GOOD - No CLS:
@php
    $widths = ['w-24', 'w-28', 'w-32', 'w-28'];
@endphp

<div class="skeleton-text {{ $widths[$i % count($widths)] }}">...</div>
```

**Benefits:**

- ✅ Consistent layout on every render
- ✅ Zero Cumulative Layout Shift
- ✅ Better Core Web Vitals scores
- ✅ Predictable, professional appearance
- ✅ Uses Tailwind classes (better for bundle size)

### Implementation

All skeleton components now use fixed dimensions:

**skeleton-card.blade.php:**

- Fixed `h-20` (80px) for content boxes
- No inline styles

**skeleton-list.blade.php:**

- Cycling array of widths: `['w-3/4', 'w-4/5', 'w-5/6']`
- Fixed `w-20 h-8` for action buttons

**skeleton-table.blade.php:**

- Predictable header widths: `['w-16', 'w-20', 'w-24', ...]`
- Predictable cell widths: `['w-24', 'w-28', 'w-32', ...]`
- Uses modulo operator for cycling through widths

---

## Testing

Skeleton loaders are covered by feature tests:

**Test File:** `tests/Feature/DesignSystemComponentsTest.php`

**Tests include:**

- Component rendering
- Class presence
- Blade compilation

**Run tests:**

```bash
ddev exec vendor/bin/phpunit --filter DesignSystemComponentsTest
```

---

## Examples

### Example 1: Custom Skeleton Card

```blade
<x-skeleton-card :rows="5" class="w-full max-w-md" />
```

### Example 2: Loading State with Message

```blade
<div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 200)">
    <div x-show="loading" class="text-center">
        <x-skeleton-list :items="3" />
        <p class="text-slate-400 mt-4">Loading your tickets...</p>
    </div>
    <div x-show="!loading" x-transition>
        <!-- content -->
    </div>
</div>
```

### Example 3: Conditional Skeleton

```blade
@if($loading)
    <x-skeleton-table :rows="10" :cols="8" />
@else
    <!-- table content -->
@endif
```

---

## Summary

Skeleton loaders have been strategically integrated throughout the application to:

✨ **Improve perceived performance** - Users see immediate feedback  
✨ **Reduce bounce rate** - No more blank screens  
✨ **Match modern UX standards** - Following 2025 design best practices  
✨ **Enhance user confidence** - Clear indication that content is loading  
✨ **Maintain consistency** - Unified loading experience across the app

**Total Integration Points:** 4 major pages  
**Components Created:** 3 reusable skeleton components  
**Test Coverage:** ✅ All components tested  
**Performance Impact:** Minimal (CSS-only animations)

---

**Last Updated:** October 14, 2025  
**Related:** Design System Modernization 2025
