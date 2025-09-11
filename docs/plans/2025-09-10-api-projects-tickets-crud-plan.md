# Plan: REST API for Projects & Tickets CRUD (v1)

## Goals
- Secure, admin-only token-authenticated API to manage Projects and Tickets.
- Clean architecture: Controllers thin, validation via Form Requests, output via Resources, auth via middleware + policies.
- Versioned under `/api/v1` with pagination, filtering, sorting.

## Assumptions
- Laravel 12, PHP 8.3+.
- Models `Project`, `Ticket` exist with relations (`Ticket` belongs to `Project`).
- Optional SoftDeletes; adjust queries if enabled.

## Tasks
1) Routing
- Create `routes/api.php` v1 group with middleware stack: `api`, `throttle:api`, `token.admin`.
- Resources (except create/edit): `Route::apiResource('projects', ProjectsController::class);` and `Route::apiResource('tickets', TicketsController::class);`

2) Authentication & Authorization
- Implement `token.admin` middleware:
  - Accept `Authorization: Bearer <token>`.
  - Compare against `config('api.admin_token')` or `env('API_ADMIN_TOKEN')` via config file `config/api.php`.
  - Optionally, when using Sanctum, ensure token belongs to an admin user.
- Add `Policy` checks (admin-only) or gate `isAdmin`.

3) Validation (Form Requests)
- `ProjectStoreRequest`, `ProjectUpdateRequest`
- `TicketStoreRequest`, `TicketUpdateRequest`
- Rules (draft):
  - Project: `name|required|string|max:120`, `description|nullable|string`, `status|in:active,archived` (default active)
  - Ticket: `project_id|required|exists:projects,id`, `title|required|string|max:200`, `description|nullable|string`, `status|in:open,in_progress,done,closed`, `priority|in:low,medium,high,urgent`

4) Controllers (thin)
- Inject Form Requests and return `JsonResource` responses.
- Index: filtering (`search`, `status`, `project_id`), sorting (`sort` like `-created_at,title`), paginate.
- Show: findOrFail, load relations as needed (e.g., `project`).
- Store/Update: validated data, return 201 on store, 200 on update.
- Destroy: 204 no content.

5) Resources (transformers)
- `ProjectResource`, `ProjectCollection`
- `TicketResource`, `TicketCollection`
- Include canonical fields and timestamps. Hide internal columns.

6) Query helpers
- Add local scopes on models for `search`, `status`, `project`.
- Index queries: avoid N+1, add `->with('project')` for tickets.

7) Error handling & responses
- Standardize error JSON (Laravel default for validation). Set 401 for missing/invalid token; 403 for non-admin.
- Wrap model not found with 404.

8) Config & env
- Create `config/api.php` with `admin_token` and `version`.
- Document `.env` variable `API_ADMIN_TOKEN` and recommend rotation.

9) Tests
- Feature tests for all endpoints (happy path + auth failures + validation errors).
- Use `withoutExceptionHandling()` where helpful.
- If Sanctum path enabled, seed admin user + token.

10) Docs
- Update `README` API section and add curl examples.

## Endpoint Specs

Projects
- GET `/api/v1/projects`
  - Query: `search`, `sort` (e.g., `-created_at,name`), `page[size]`, `page[number]`
  - 200 -> `ProjectCollection`
- POST `/api/v1/projects`
  - Body: `{ name, description?, status? }`
  - 201 -> `ProjectResource`
- GET `/api/v1/projects/{id}` -> 200 or 404
- PATCH `/api/v1/projects/{id}` -> 200
- DELETE `/api/v1/projects/{id}` -> 204

Tickets
- GET `/api/v1/tickets`
  - Query: `project_id`, `search`, `status`, `sort`, `page[]`
  - 200 -> `TicketCollection`
- POST `/api/v1/tickets`
  - Body: `{ project_id, title, description?, status?, priority? }`
  - 201 -> `TicketResource`
- GET `/api/v1/tickets/{id}` -> 200 or 404
- PATCH `/api/v1/tickets/{id}` -> 200
- DELETE `/api/v1/tickets/{id}` -> 204

## Data Shapes

ProjectResource
```json
{
  "data": {
    "id": 1,
    "name": "Website Redesign",
    "description": "...",
    "status": "active",
    "created_at": "2025-09-10T12:34:56Z",
    "updated_at": "2025-09-10T12:34:56Z"
  }
}
```

TicketResource
```json
{
  "data": {
    "id": 10,
    "project_id": 1,
    "title": "Fix login redirect",
    "description": "...",
    "status": "open",
    "priority": "medium",
    "created_at": "2025-09-10T12:34:56Z",
    "updated_at": "2025-09-10T12:34:56Z"
  }
}
```

## Security
- Middleware only accepts HTTPS and Bearer tokens.
- Token compared in constant-time (`hash_equals`).
- Add rate limiting: `throttle:60,1` or customized.
- Log auth failures without leaking token values.

## Open Questions
- Do we use Sanctum tokens bound to admin users or a static deployment token? (Default: static `API_ADMIN_TOKEN`).
- Soft delete behavior and restoration endpoints? (Out of scope for v1.)
- Additional relations in Ticket (assignees, comments) to embed? (Out of scope for v1.)
