[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F4ef06724-84ca-448c-bdc6-0e1b5cc961b1%3Fdate%3D1%26label%3D1%26commit%3D1&style=plastic)](https://forge.laravel.com/servers/956420/sites/2838828)

# 🔥 IssueForge

### Forge Solutions From Issues

A modern, self‑hosted ticket system and Redmine alternative — built with Laravel 12, Livewire 3, and Tailwind CSS, featuring a cohesive dark design system.

---

## ✨ Features

- 🎫 **Projects & Tickets**: Organize work by project, track tasks and bugs
- 🔗 **Human URLs**: Slugs for projects and tickets (web); API uses numeric ids
- 🔎 **Global search**: Matches projects/tickets; supports ticket ids (`123`, `#123`, `PREFIX-123`)
- 👥 **Role & policy based**: Spatie roles/permissions + policies (admins see Users nav)
- 🔔 **Notifications JSON**: Session-auth JSON endpoints for in-app notifications
- 🌙 **Modern dark UI**: Animated, glassmorphism-inspired, responsive
- ⚡ **Reactive UI**: Livewire components for dynamic interactions
- 🔐 **Token REST API**: Versioned API for Projects and Tickets (bearer token)
- 📱 **Responsive**: Polished experience across devices

---

## 🚀 Tech Stack

- **Backend**: Laravel 12, PHP 8.3
- **Frontend**: Livewire 3, Blade, Alpine.js
- **Styling**: Tailwind CSS, Tailwind Typography, custom dark design system
- **Auth**: Laravel Breeze, Spatie Permission
- **Dev**: DDEV, Vite (HMR)
- **DB**: MariaDB/MySQL (SQLite/PostgreSQL compatible)

---

## 🛠️ Installation

### Prerequisites

- DDEV installed and configured
- Git

### Setup

```bash
# Clone
git clone <repository-url> issue-forge
cd issue-forge

# Start DDEV
ddev start

# Install deps (PHP + Node)
ddev composer install
ddev exec npm install

# Env
cp .env.example .env
ddev exec php artisan key:generate

# Optional: enable API token auth
echo "API_ADMIN_TOKEN=$(openssl rand -hex 24)" >> .env
echo "API_VERSION=v1" >> .env
ddev exec php artisan config:clear

# DB
ddev exec php artisan migrate --seed

# Build assets (prod) or run dev server
ddev exec npm run build
# or
ddev exec npm run dev
```

### Testing & Linting

See `docs/TESTING_AND_LINTING.md` for full details.

Quick commands (inside DDEV):

```bash
# PHP
vendor/bin/pint --test
vendor/bin/phpunit --colors=always

# JS/CSS
npm run lint
```

### Admin user

Seeders create a default user set for local development. You can create additional users via the UI or tinker.

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/UserController.php        # User management (policy-protected)
│   │   ├── Api/ProjectsController.php      # REST API (projects)
│   │   ├── Api/TicketsController.php       # REST API (tickets)
│   │   ├── NotificationController.php      # JSON notifications (web session)
│   │   ├── ProjectController.php           # Web: Project CRUD
│   │   └── TicketController.php            # Web: Ticket CRUD
│   ├── Middleware/AdminTokenMiddleware.php # Bearer token guard for API
│   └── Livewire/ProjectList.php            # Project listing
├── Http/Requests/Api/                      # Form requests for API validation
├── Http/Resources/                         # API Resources (Project*, Ticket*)
├── Models/
│   ├── User.php                            # Slug from name (web), roles/permissions
│   ├── Project.php                         # Slug (web), scope helpers
│   └── Ticket.php                          # Slug from title (web), ticket number helper
├── Policies/                               # User policy for admin area
config/api.php                               # API version + admin token
routes/
├── web.php                                 # Web routes (auth)
└── api.php                                 # Versioned API (/api/v1)
resources/
├── css/app.css                             # Custom dark design system
├── views/                                  # Blade views (admin, projects, tickets)
└── js/app.js                               # Frontend bootstrap
docs/
└── api.md                                  # Full API documentation
```

---

## 🎨 Design System

IssueForge ships a cohesive Tailwind-based dark design system:

- **Colors**: Dark palette with primary/accent hues
- **Components**: Buttons, cards, inputs, badges
- **Animations**: Fade-in, slide-in, hover-lift, subtle glow
- **Glassmorphism**: Backdrop blur with tasteful borders
- **Typography**: Inter font; Tailwind Typography for Markdown (`prose`, `prose-invert`)

Available utility classes (non-exhaustive): `btn-primary`, `btn-secondary`, `btn-danger`, `btn-ghost`, `card`, `card-hover`, `card-glass`, `input`, `badge-*`, `animate-fade-in-up`.

---

## 🔐 Security

- **No public signup** — only admins create users
- **Authorization** via policies and Spatie roles/permissions
- **API token** via `API_ADMIN_TOKEN` (Bearer), middleware `token.admin`
- **CSRF** protection enabled by default
- **Password hashing** using Laravel defaults (bcrypt/argon2)
- **Validation** via Form Requests

---

## 📊 User Management

- Admins see the `Users` navigation and can manage users
- Routes are policy-protected via `UserPolicy` (`authorizeResource` in controller)

---

## 🎯 Roadmap

- [x] Token-protected REST API (projects, tickets)
- [x] Search enhancements (ticket id/number)
- [x] Ticket comments & discussions
- [x] Email notifications (Brevo SMTP/API)
- [ ] File attachments
- [ ] Extended workflow states (blocked, needs review, reopened, status history)
- [ ] Advanced search and filters
- [ ] Reports & analytics
- [ ] Mobile app

---

## 🔌 REST API (Quick)

Full docs in `docs/api.md`.

```bash
export BASE=https://issue-forge.ddev.site/api/v1
export TOKEN=your-long-random-token
AUTH=(-H "Authorization: Bearer $TOKEN" -H "Accept: application/json" -H "Content-Type: application/json")

curl -s "${AUTH[@]}" "$BASE/projects?sort=-id" | jq
```

---

## 🤝 Contributing

Pull requests are welcome. Please follow:

- **Code style**: PSR‑12 for PHP, Prettier/ESLint for JS/CSS
- **Commits**: Conventional Commits (`feat:`, `fix:`, `chore:`)
- **Tests**: Add feature/unit tests where appropriate
- **Dark UI**: Ensure new UI matches the dark design system

---

## 📄 License

MIT License — free for commercial and private use.

---

## 💡 Inspiration

- Redmine (functionality)
- Linear.app (design)
- GitHub Issues (UX)

---

## 📬 Support

**IssueForge** — Open Source Project  
📧 [support@issue-forge.com](mailto:support@issue-forge.com)

---

> **IssueForge** — Where issues get forged into solutions. 🔥⚡
