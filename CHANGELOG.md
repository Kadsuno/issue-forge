## 2025-10-14

### Added

- SMTP Email Provider Setup: Configured Brevo (Sendinblue) as the transactional email provider
  - Created comprehensive `docs/EMAIL_SETUP.md` documentation with setup instructions, DNS verification, and troubleshooting
  - Configured DDEV to use Mailpit for local development (port 1025, accessible at http://issue-forge.ddev.site:8025)
  - Updated `config/mail.php` to remove deprecated `scheme` parameter and add `verify_peer` security setting
  - Added Brevo API configuration to `config/services.php` for optional HTTP API fallback
  - Added comprehensive email configuration feature tests covering SMTP settings, notifications, and environment checks
  - Environment variable support for MAIL_EHLO_DOMAIN and MAIL_VERIFY_PEER

### Changed

- Enhanced SMTP mailer configuration with explicit `encryption` parameter and SSL verification
- Mail configuration now properly supports both development (Mailpit) and production (Brevo SMTP) environments

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
