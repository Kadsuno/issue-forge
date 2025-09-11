## 2025-09-10

### Added

- API v1 for Projects and Tickets: CRUD endpoints under `/api/v1/*`.
- Admin bearer token auth via `token.admin` middleware and `API_ADMIN_TOKEN`.
- Form Requests for validation, API Resources for responses.
- Feature tests covering basic CRUD and auth scenarios.

## 2025-09-10

- feat: Move project and ticket descriptions out of headers into a dedicated card below the title. Added reusable `x-description-card` component, updated `projects.show` and `tickets.show`, and added feature tests to assert placement.
