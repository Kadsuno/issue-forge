### Plan: Move project/ticket descriptions beneath titles in separate container

#### Goal

Keep headers compact by relocating long descriptions below the title in a dedicated, styled container on Project and Ticket detail pages.

#### Scope

- Project show page
- Ticket show page
- Any shared header layout/component they use

#### Acceptance Criteria

- Header shows title + meta/actions only; no long description.
- Description renders directly under header within a dark-themed card container (`card`/`card-glass`) with `p-6` spacing and `max-w-3xl` width.
- Description supports sanitized Markdown rendering.
- Consistent spacing and responsive behavior.

#### Implementation Steps

1. Inventory current rendering:
    - Identify views/components for project and ticket detail headers.
    - Locate where descriptions are currently output.

2. Create shared description component:
    - `resources/views/components/description-card.blade.php`
    - Props: `content` (already-sanitized HTML) or `markdown` (string to render via helper), `maxWidth` (default `max-w-3xl`).
    - Markup uses design system classes: `card p-6` and wraps output.

3. Centralize description rendering:
    - Add a Markdown-to-HTML helper/service if not present (e.g., league/commonmark or existing TALL editor parser). Ensure HTML is sanitized.
    - Expose method in a dedicated service (e.g., `App\Services\Content\MarkdownRenderer`).

4. Update pages:
    - Remove description from header partials.
    - Insert `<x-description-card :content="$renderedDescription" />` below header on project/ticket show.
    - Ensure Livewire components (if used) pass rendered/sanitized content.

5. Styling and spacing:
    - Add `mt-4 md:mt-6` spacing from header to description card.
    - Confirm dark theme, hover/focus states for links inside description.

6. Security & a11y:
    - Sanitize HTML (e.g., `HTMLPurifier`/`graham-campbell/security` or `Illuminate\Support\Str::of(...)->sanitize()` if available).
    - Prevent heading level misuse inside description (strip `<h1>` or down-level to `<h2>`+ if necessary).

7. Tests:
    - Feature tests for project/ticket show pages asserting header does not contain description text and page contains description in a `.card` container under header.
    - Unit test for MarkdownRenderer ensuring sanitization and basic formatting (links, lists, code) render.

8. Docs & cleanup:
    - Note component usage in `DESIGN_SYSTEM.md` (Description Card section).

#### Rollback Plan

- Revert Blade edits and component; keep renderer since it's generic.

#### Risks / Mitigations

- XSS via descriptions → enforce sanitization and encode by default; only `{!! !!}` trusted sanitized output.
- Visual regressions → verify on mobile/desktop; snapshot or Dusk tests if available.

#### Effort

- ~2–4 hours depending on existing renderer/component reuse.
