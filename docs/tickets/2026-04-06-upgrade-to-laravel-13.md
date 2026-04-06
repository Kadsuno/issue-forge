## Title
Upgrade framework to Laravel 13

## Summary
Bump `laravel/framework` and aligned first-party / dev dependencies to Laravel 13, require PHP 8.4+ (Symfony 8), sync framework config stubs where required, and verify the full test suite so IssueForge stays on a supported stack.

## Background / Context
- **Current state**: `composer.json` pinned `laravel/framework` to `^12.0`; README listed Laravel 12.
- **Why**: Stay current with Laravel 13 and Symfony 8; reduce security and ecosystem drift.
- **Official guide**: [Upgrade Guide — Laravel 13.x](https://laravel.com/docs/13.x/upgrade).
- **Note**: Resolved dependency tree requires **PHP 8.4+** (not only 8.3) due to Symfony 8.

## Requirements
- [x] `composer.json` / lockfile with `laravel/framework` `^13.0` and compatible packages (`laravel/tinker` `^3.0`, PHPUnit 12, etc.).
- [x] PHP requirement `^8.4` to match Composer platform (Symfony 8).
- [x] Config aligned with Laravel 13 (`cache.serializable_classes`).
- [x] `.env.example` optional notes for `CACHE_PREFIX`, `REDIS_PREFIX`, `SESSION_COOKIE`.
- [x] `README.md` / `CHANGELOG.md` updated.
- [x] PHPUnit green; Pint clean.

## Technical notes (optional)
- DDEV: `.ddev/config.yaml` `php_version` set to `8.4`.
- PHPUnit in CI/local: PHP container needs GD for attachment tests (same as before).

## Testing
- `vendor/bin/phpunit` (PHP 8.4, GD enabled for file tests).
- `vendor/bin/pint --test`.

## Impact / Risks
- **Runtime**: Hosts must run PHP **8.4+**.
- **Cache/session**: Pin env vars if relying on stable keys across upgrades.
