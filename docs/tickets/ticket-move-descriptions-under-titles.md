## Move project/ticket descriptions beneath titles in a separate container

### Bug/Feature

Feature (UI/UX improvement)

### Environment

Dev/Staging (apply to all environments after review)

### System

Laravel v12.x, Livewire 3, Blade, Tailwind CSS (Modern Dark Design System)

### Affected Components

- Views: `resources/views/projects/show.blade.php`
- Views: `resources/views/tickets/show.blade.php`
- Shared header component (if present): `resources/views/components/page-header.blade.php` or similar
- Any Livewire components rendering project/ticket headers

### Specification

- Remove description text from the page header for Project and Ticket detail pages.
- Render the description directly below the title in a distinct container to keep the header compact.
- Container requirements:
    - Full-width within content area, constrained to readable width (e.g., `max-w-3xl`), centered.
    - Uses dark design system surfaces (e.g., `card`/`card-glass`), spacing `p-4 md:p-6`, and subtle divider from header.
    - Supports Markdown rendering (same as current description rendering, if any) and safe HTML (sanitized).
    - Responsive and accessible: proper headings order, readable contrast, focusable links.
- Header keeps: title, meta, actions. Header must not include the long description anymore.
- Apply consistently to both Project and Ticket show pages.

### Code Examples

Example Blade structure sketch (for both Project and Ticket show pages):

```blade
<x-page-header>
  <h1 class="text-2xl md:text-3xl font-semibold">{{ $model->title }}</h1>
  <x-slot:name>meta/actions here</x-slot:name>
</x-page-header>

<section class="mt-4 md:mt-6">
  <div class="card p-6 max-w-3xl">
    {!! $renderedDescription !!}
  </div>
  {{-- $renderedDescription should be sanitized Markdown/HTML --}}
</section>
```

### Expected Result

- The header remains compact with only the title, metadata, and actions.
- The description appears directly beneath the header inside its own container, following the dark design system, without pushing the header down excessively.
- Works identically for Projects and Tickets.

### Hints/Risks

- Ensure Markdown/HTML is sanitized to avoid XSS.
- Maintain a11y and heading hierarchy; do not nest additional h1 elements inside the description.
- Keep consistent spacing between header and description across screens.
- Watch for duplicated rendering paths (Livewire vs Blade partials) and unify via a shared component where possible.

### Attachments

- Screenshot provided in conversation (current header appears bloated due to long description).
