# Feature: REST API for Projects & Tickets CRUD

Enviroment: Live / Staging / Local

System: Laravel v12.x (PHP 8.3+), Sanctum or Token-based Admin Auth

Affected Components
- Routes: `routes/api.php`
- Controllers: `app/Http/Controllers/Api/ProjectsController.php`, `app/Http/Controllers/Api/TicketsController.php`
- Requests: `app/Http/Requests/Api/*`
- Resources: `app/Http/Resources/*`
- Policies/Gates: `app/Policies/*`
- Middleware: token auth middleware

Specification
- Provide authenticated REST API endpoints to create, read, update, delete Projects and Tickets.
- Authentication: Static personal access token + admin-only restriction (role=admin). Token sent via `Authorization: Bearer <token>`.
- Versioned API under `/api/v1`.
- Use Form Requests for validation. Return JSON:API-ish responses with consistent error shape.
- Include filtering, sorting, pagination for list endpoints.
- Soft delete support if models use SoftDeletes (speculative) — return `deleted_at` and exclude by default, with `?with_trashed=1` for admins.
- Eager-load common relations to minimize N+1.

Endpoints (v1)
Projects
- GET   `/api/v1/projects` — list (paginate, `?search=`, `?sort=`, `?page=`)
- POST  `/api/v1/projects` — create
- GET   `/api/v1/projects/{id}` — show
- PATCH `/api/v1/projects/{id}` — update (partial)
- DELETE `/api/v1/projects/{id}` — delete

Tickets
- GET   `/api/v1/tickets` — list (paginate, `?project_id=`, `?search=`, `?status=`, `?sort=`)
- POST  `/api/v1/tickets` — create
- GET   `/api/v1/tickets/{id}` — show
- PATCH `/api/v1/tickets/{id}` — update (partial)
- DELETE `/api/v1/tickets/{id}` — delete

Authentication
- Header: `Authorization: Bearer <TOKEN>`
- Tokens managed via env var `API_ADMIN_TOKEN` or Sanctum tokens assigned to admin users.
- Access restricted to users with admin role; otherwise 403.

Validation (examples)
- Project: `name:string|required|max:120`, `description:string|nullable`, `status:in:active,archived`
- Ticket: `project_id:exists:projects,id|required`, `title:string|required|max:200`, `description:string|nullable`, `status:in:open,in_progress,done,closed`, `priority:in:low,medium,high,urgent`

Code Examples

Request example (create project):
```bash
curl -X POST \
  -H "Authorization: Bearer $API_ADMIN_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"Website Redesign","description":"Marketing site revamp"}' \
  https://your-host/api/v1/projects
```

Response example:
```json
{
  "data": {
    "id": 42,
    "name": "Website Redesign",
    "description": "Marketing site revamp",
    "status": "active",
    "created_at": "2025-09-10T12:34:56Z",
    "updated_at": "2025-09-10T12:34:56Z"
  }
}
```

Error shape (validation):
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."]
  }
}
```

Expected Result
- Fully functional, secured CRUD API for Projects and Tickets.
- Admin-only access via token; requests without valid token receive 401/403.
- Consistent JSON responses and error formats.
- Pagination and basic filtering/sorting supported.

Hints/Risks
- Ensure idempotent migrations if adding indices for filters.
- Rate-limit API to mitigate abuse (e.g., `throttle:api`).
- Validate and authorize via Form Requests + Policies.
- Consider API Resources to shape output and hide internal fields.
- Beware N+1 queries; add indexes for `project_id`, `status`.
- If mixing Sanctum and static tokens, clearly separate middleware.

Attachments
- None yet.
