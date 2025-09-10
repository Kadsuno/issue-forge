[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F4ef06724-84ca-448c-bdc6-0e1b5cc961b1%3Fdate%3D1%26label%3D1%26commit%3D1&style=plastic)](https://forge.laravel.com/servers/956420/sites/2838828)

# 🔥 IssueForge

### Forge Solutions From Issues

A modern, self‑hosted ticket system and Redmine alternative — built with Laravel 12, Livewire 3, and Tailwind CSS, featuring a cohesive dark design system.

---

## ✨ Features

- 🎫 **Projects & Tickets**: Organize work by project, track tasks and bugs
- 👥 **Role-based access**: Admin and standard users with proper authorization
- 💬 **Markdown everywhere**: Rich text for descriptions and comments (server-side rendered)
- 🌙 **Modern dark UI**: Animated, glassmorphism-inspired, responsive
- ⚡ **Reactive UI**: Livewire components for dynamic interactions
- 🔐 **Secure admin area**: Only admins manage users
- 📱 **Responsive**: Polished experience across devices

---

## 🚀 Tech Stack

- **Backend**: Laravel 12, PHP 8.3
- **Frontend**: Livewire 3, Blade, Alpine.js
- **Styling**: Tailwind CSS, Tailwind Typography, custom dark design system
- **Auth**: Laravel Breeze
- **Dev**: DDEV, Vite (HMR)
- **DB**: SQLite / MySQL / PostgreSQL

---

## 🛠️ Installation

### Prerequisites

- DDEV installed and configured
- Git

### Setup

```bash
# Clone the repository
git clone <repository-url> issueforge
cd issueforge

# Start DDEV
ddev start
## Testing & Linting

See `docs/TESTING_AND_LINTING.md` for full details.

Quick commands (inside DDEV):

```bash
# PHP
vendor/bin/pint --test
vendor/bin/phpunit --colors=always

# JS/CSS
npm run lint
```


# Install dependencies
ddev composer install
ddev exec npm install

# Environment
cp .env.example .env
ddev exec php artisan key:generate

# Database
ddev exec php artisan migrate
ddev exec php artisan db:seed --class=AdminUserSeeder

# Build assets
ddev exec npm run build
```

### Development

```bash
# Run Vite dev server with HMR inside DDEV
ddev exec npm run dev
```

### Admin user

The seeder creates a default admin account for local development. Check `database/seeders/AdminUserSeeder.php` for credentials or create your own user via tinker.

---

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/UserController.php    # User management
│   │   ├── ProjectController.php       # Project CRUD
│   │   └── TicketController.php        # Ticket CRUD
│   └── Livewire/
│       └── ProjectList.php             # Project listing
├── Models/
│   ├── User.php                        # User with admin flag
│   ├── Project.php                     # Project model
│   └── Ticket.php                      # Ticket model
resources/
├── css/app.css                         # Custom design system
├── views/                              # Blade views (admin, projects, tickets, livewire)
└── js/app.js                           # Frontend bootstrap
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
- **Authorization** via policies and middleware
- **CSRF** protection enabled by default
- **Password hashing** using Laravel defaults (bcrypt/argon2)
- **Validation** via Form Requests

---

## 📊 User Management

### Admin capabilities

- Create, edit, delete users
- Grant/revoke admin privileges
- Reset passwords
- See basic stats

### Access control

```php
// Admin routes example
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
});
```

---

## 🎯 Roadmap

- [ ] Ticket comments and discussions
- [ ] File attachments
- [ ] Extended workflow states
- [ ] Notifications (email/browser)
- [ ] REST API endpoints
- [ ] Advanced search
- [ ] Reports & analytics
- [ ] Mobile app

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
