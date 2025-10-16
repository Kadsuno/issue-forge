# IssueForge API

A minimal, token-protected REST API for Projects and Tickets.

## Base URL and Versioning

- Base URL (DDEV): `https://issue-forge.ddev.site/api/v1`
- Version is configurable via `config/api.php` or env `API_VERSION` (default: `v1`).

## Authentication

- Scheme: Bearer token, header `Authorization: Bearer <token>`
- Configure token in `.env`:

```env
API_ADMIN_TOKEN=your-long-random-token
API_VERSION=v1
```

- Ensure config is reloaded after changes:

```bash
ddev exec php artisan config:clear
```

If the token is missing or invalid:

- 401 Unauthorized → no/malformed `Authorization` header
- 403 Forbidden → token provided but does not match `API_ADMIN_TOKEN`
- 503 Service Unavailable → token not configured (empty)

## Headers

- Always send:
    - `Accept: application/json`
    - `Content-Type: application/json` for requests with a body

## Pagination, Sorting, Search

- Pagination: standard Laravel pagination. Use `?page=N`.
- Sorting: `?sort=field1,-field2` (leading `-` = DESC). Defaults to `-id` for index endpoints.
- Search:
    - Projects: `?search=...` (matches `name`)
    - Tickets: `?search=...` (matches `title`/`description`)

## Quickstart (curl)

```bash
export BASE=https://issue-forge.ddev.site/api/v1
export TOKEN=your-long-random-token
AUTH=(-H "Authorization: Bearer $TOKEN" -H "Accept: application/json" -H "Content-Type: application/json")

# List projects
curl -s "${AUTH[@]}" "$BASE/projects?sort=-id" | jq

# Create a project
curl -s "${AUTH[@]}" -X POST "$BASE/projects" \
  -d '{"name":"Demo Project","description":"Created via API"}' | jq
```

---

## Resources

### Project

Base path: `/projects`

#### GET /projects

List projects.

Query params:

- `search` (string)
- `sort` (comma-separated, e.g., `-id,name`)
- `page` (int)

Response 200 body (excerpt):

```json
{
    "data": [
        {
            "id": 123,
            "name": "Demo",
            "description": "...",
            "status": "active",
            "created_at": "2025-09-11T20:00:00+00:00",
            "updated_at": "2025-09-11T20:00:00+00:00"
        }
    ],
    "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
    "meta": { "current_page": 1, "from": 1, "to": 1, "total": 1 }
}
```

#### POST /projects

Create a project.

Request JSON:

- `name` (string, required, max 120)
- `description` (string, optional)
- `status` ("active" | "archived", optional)
- `user_id` (int, optional; defaults to the first user if omitted)

Response 201 body: `ProjectResource` (see fields below)

#### GET /projects/{id}

Show a project by numeric id.

Response 200 body: `ProjectResource`

#### PATCH /projects/{id}

Update a project.

Body (any subset):

- `name` (string, max 120)
- `description` (string|null)
- `status` ("active" | "archived")

Response 200 body: `ProjectResource`

#### DELETE /projects/{id}

Delete a project.

Response 204 body: empty

#### ProjectResource fields

- `id` (number)
- `name` (string)
- `description` (string|null)
- `status` ("active" | "archived")
- `created_at` (ISO string|null)
- `updated_at` (ISO string|null)

---

### Ticket

Base path: `/tickets`

#### GET /tickets

List tickets.

Query params:

- `project_id` (int) — filter by project
- `status` ("open" | "in_progress" | "resolved" | "closed")
- `search` (string)
- `sort` (e.g., `-id,title`)
- `page` (int)

Response 200 body (excerpt):

```json
{
    "data": [
        {
            "id": 456,
            "project_id": 123,
            "title": "Fix login",
            "description": "...",
            "status": "open",
            "priority": "medium",
            "created_at": "...",
            "updated_at": "..."
        }
    ],
    "links": {},
    "meta": {}
}
```

#### POST /tickets

Create a ticket.

Request JSON:

- `project_id` (int, required)
- `title` (string, required, max 200)
- `description` (string|null)
- `status` ("open" | "in_progress" | "resolved" | "closed")
- `priority` ("low" | "medium" | "high" | "urgent")
- `user_id` (int, optional; defaults to first user if omitted)

Response 201 body: `TicketResource`

#### GET /tickets/{id}

Show a ticket by numeric id.

Response 200 body: `TicketResource`

#### PATCH /tickets/{id}

Update a ticket.

Body (any subset):

- `project_id` (int)
- `title` (string, max 200)
- `description` (string|null)
- `status` ("open" | "in_progress" | "resolved" | "closed")
- `priority` ("low" | "medium" | "high" | "urgent")

Response 200 body: `TicketResource`

#### DELETE /tickets/{id}

Delete a ticket.

Response 204 body: empty

#### TicketResource fields

- `id` (number)
- `project_id` (number)
- `title` (string)
- `description` (string|null)
- `status` ("open" | "in_progress" | "resolved" | "closed")
- `priority` ("low" | "medium" | "high" | "urgent")
- `created_at` (ISO string|null)
- `updated_at` (ISO string|null)

---

## Error Responses

- 401 Unauthorized — missing or malformed `Authorization` header
- 403 Forbidden — token mismatch
- 404 Not Found — resource does not exist
- 422 Unprocessable Entity — validation errors (field-wise messages)
- 429 Too Many Requests — throttled by `throttle:api`
- 500 Server Error — unexpected error (see logs)

Validation errors (example):

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": ["The name field is required."]
    }
}
```

---

## Useful Dev Commands

```bash
# List API routes
ddev exec php artisan route:list --path=api/v1 | cat

# Clear config cache after env changes
ddev exec php artisan config:clear

# Tail logs
ddev exec tail -f storage/logs/laravel.log
```

---

## Workflow States

Base path: `/workflows`

### GET /workflows/states

Get all global workflow states.

Response 200 body:

```json
{
  "data": [
    {
      "id": 1,
      "slug": "open",
      "label": "Open",
      "description": "Ticket is open and awaiting assignment or action",
      "color": "gray",
      "icon": "circle",
      "is_closed": false,
      "is_predefined": true,
      "order": 1,
      "project_id": null
    },
    {
      "id": 2,
      "slug": "in_progress",
      "label": "In Progress",
      "description": "Ticket is actively being worked on",
      "color": "blue",
      "icon": "arrow-right",
      "is_closed": false,
      "is_predefined": true,
      "order": 2,
      "project_id": null
    }
  ]
}
```

### GET /projects/{id}/workflows/states

Get available workflow states for a specific project. Returns global states or project-specific states depending on the project's `workflow_type` setting.

Response 200 body: Same structure as above, filtered based on project configuration.

---

### Extended Ticket Resource

Tickets now include workflow state details and available transitions:

```json
{
  "id": 456,
  "project_id": 123,
  "title": "Fix login",
  "description": "...",
  "status": "in_progress",
  "status_details": {
    "id": 2,
    "slug": "in_progress",
    "label": "In Progress",
    "description": "Ticket is actively being worked on",
    "color": "blue",
    "icon": "arrow-right",
    "is_closed": false,
    "is_predefined": true,
    "order": 2,
    "project_id": null
  },
  "priority": "medium",
  "available_transitions": [
    {"slug": "testing", "label": "Testing", "color": "purple"},
    {"slug": "blocked", "label": "Blocked", "color": "red"},
    {"slug": "resolved", "label": "Resolved", "color": "green"}
  ],
  "created_at": "...",
  "updated_at": "..."
}
```

**Note**: The `status` field now accepts workflow state slugs instead of enum values. Valid values depend on the project's workflow configuration. Use the workflow states endpoints to get available options.

---

## Notes

- API uses numeric ids for resources (web uses slugs for some routes).
- `user_id` is optional on create in development; production should specify an explicit user.
- Sorting and pagination can be combined: `?sort=-id&page=2`.
- Ticket `status` field now uses workflow state slugs. Check available states via `/projects/{id}/workflows/states`.
- Consider enabling per-user tokens (Sanctum) and adding an OpenAPI spec if you need client SDKs.
- See `docs/WORKFLOW_SYSTEM.md` for comprehensive workflow documentation.
