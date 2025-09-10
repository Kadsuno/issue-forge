### Testing & Linting Guide

This project runs all PHP and JS/CSS tooling inside DDEV. The commands below assume you are in the project root.

#### Prerequisites
- DDEV installed and configured
- Project started: `ddev start`

#### One-time setup (JS/CSS)
If you’ve just pulled the repo or changed node deps:

```bash
ddev npm install
```

#### PHP Testing (PHPUnit)

Run the full test suite:

```bash
ddev exec vendor/bin/phpunit --colors=always
```

Run a specific test file:

```bash
ddev exec vendor/bin/phpunit tests/Feature/ProjectAndTicketDescriptionPlacementTest.php --colors=always
```

Common issues:
- SQLite + migrations: prefer idempotent migrations (drop indices before dropping columns, guard with `Schema::hasColumn`).

#### PHP Linting/Formatting (Pint)

Check formatting without changes:

```bash
ddev exec vendor/bin/pint --test
```

Auto-fix formatting:

```bash
ddev exec vendor/bin/pint
```

#### JS/CSS Linting & Prettier

All-in-one check (ESLint, Stylelint, Prettier check):

```bash
ddev npm run lint
```

Run individually:

```bash
# ESLint (JS/TS, flat config)
ddev npm run lint:js

# Stylelint (CSS/SCSS)
ddev npm run lint:css

# Prettier (check only)
ddev npm run format:check

# Prettier (auto-fix)
ddev npm run format
```

Notes:
- ESLint uses flat config `eslint.config.js` (ESLint v9+). The legacy `.eslintrc.cjs` is retained for editor compatibility but unused by CLI.
- Stylelint config is tailored to Tailwind and our custom utilities. Strict rules that conflict with Tailwind (e.g., color function notation) are disabled to reduce noise.
- Prettier formats Markdown, YAML, JSON, CSS, and JS. Tailwind class sorting is enabled via `prettier-plugin-tailwindcss`.

#### CI Suggestions (optional)

Recommended command set:

```bash
ddev exec vendor/bin/pint --test
ddev exec vendor/bin/phpunit --colors=always
ddev exec npm run lint:js
ddev exec npm run lint:css
ddev exec npm run format:check
```

#### Troubleshooting
- npm ETARGET / peer conflicts: clear lockfile and `node_modules`, ensure package versions exist, and prefer the ranges in `package.json`.
- ESLint “no config” error: ensure `eslint.config.js` is present (flat config for v9+).
- DDEV not running: `ddev start -y` before executing any commands.


