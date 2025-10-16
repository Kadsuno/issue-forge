# Workflow System Documentation

## Overview

The workflow system provides flexible state management for tickets, supporting both predefined and custom workflow states. Admins can configure workflows globally or per-project, with role-based permissions controlling which users can transition tickets to specific states.

## Features

- **9 Predefined States**: open, in_progress, testing, review, waiting, blocked, resolved, wontfix, closed
- **Custom States**: Create project-specific or additional global states
- **Role-Based Permissions**: Control which roles can set tickets to specific states
- **Status History**: Automatic tracking of all status changes with timestamps and users
- **Flexible Configuration**: Choose between global workflows or project-specific workflows

## Architecture

### Database Schema

#### `workflow_states` Table

Stores all workflow state definitions:

- `id`: Primary key
- `name`: Internal name (e.g., "In Progress")
- `slug`: URL-friendly identifier (e.g., "in_progress")
- `label`: Display label for UI
- `description`: Description of the state
- `color`: UI color (gray, blue, green, yellow, orange, red, purple)
- `icon`: Optional icon identifier
- `is_predefined`: Boolean, true for system states
- `is_closed`: Boolean, marks tickets as completed
- `order`: Sort order for display
- `project_id`: NULL for global states, project ID for project-specific states

#### `workflow_state_permissions` Table

Controls role permissions for states:

- `workflow_state_id`: Foreign key to workflow_states
- `role_id`: Foreign key to roles (Spatie permissions)
- `can_set_to`: Boolean, allows role to transition to this state

#### `ticket_status_history` Table

Tracks all status changes:

- `ticket_id`: Foreign key to tickets
- `from_status`: Previous status slug
- `to_status`: New status slug
- `user_id`: User who made the change
- `comment`: Optional comment about the change
- `created_at`: Timestamp of change

### Models

#### `WorkflowState` Model

- **Scopes**:
    - `global()`: Get global states (project_id is null)
    - `forProject($projectId)`: Get states for specific project
    - `predefined()`: Get only system-defined states
    - `custom()`: Get only custom states

- **Methods**:
    - `canBeSetBy(User $user)`: Check if user has permission to use this state
    - `getColorClassAttribute()`: Get Tailwind CSS class for badge color

#### `TicketStatusHistory` Model

- Relationships: `ticket()`, `user()`
- Automatically created by TicketObserver when status changes

### Services

#### `WorkflowService`

Central service for workflow management:

```php
// Get states for a project
$states = $workflowService->getStatesForProject($project);

// Get states filtered by user permissions
$userStates = $workflowService->getStatesForUser($user, $project);

// Check if transition is allowed
$canTransition = $workflowService->canTransition($ticket, 'resolved', $user);

// Perform status transition with history logging
$workflowService->transition($ticket, 'resolved', $user, 'Fixed the bug');
```

## Configuration

### Project Workflow Type

Each project has a `workflow_type` field:

- **`global`** (default): Uses system-wide predefined states
- **`custom`**: Uses project-specific custom states

Configure in project settings:

```php
$project->update(['workflow_type' => 'custom']);
```

### Creating Custom States

1. Navigate to **Admin > Workflows**
2. Click **Create Custom State**
3. Fill in:
    - Name and Label
    - Description
    - Color (for UI badges)
    - Icon (optional)
    - Order (determines display sequence)
    - Project (leave empty for global, or select specific project)
    - Is Closed checkbox (marks tickets as completed)

### Setting Role Permissions

1. Navigate to **Admin > Workflows**
2. Click **Edit** on a custom state
3. Check roles that can set tickets to this state
4. Leave all unchecked to allow all users

**Note**: Predefined states cannot have their permissions modified except "wontfix" which requires admin or project_manager role.

## API Usage

### Get Global Workflow States

```bash
GET /api/v1/workflows/states

Response:
{
  "data": [
    {
      "id": 1,
      "slug": "open",
      "label": "Open",
      "description": "Ticket is open and awaiting assignment",
      "color": "gray",
      "icon": "circle",
      "is_closed": false,
      "is_predefined": true,
      "order": 1,
      "project_id": null
    },
    ...
  ]
}
```

### Get Project Workflow States

```bash
GET /api/v1/projects/{project}/workflows/states

Response: Same structure as above, filtered by project workflow type
```

### Create/Update Tickets with Status

```bash
POST /api/v1/tickets
{
  "project_id": 1,
  "title": "Fix login bug",
  "status": "open"  // Must be valid slug from project's available states
}
```

### Ticket Response with Workflow Data

```json
{
    "id": 123,
    "title": "Fix login bug",
    "status": "in_progress",
    "status_details": {
        "id": 2,
        "slug": "in_progress",
        "label": "In Progress",
        "color": "blue",
        "is_closed": false
    },
    "available_transitions": [
        { "slug": "testing", "label": "Testing", "color": "purple" },
        { "slug": "blocked", "label": "Blocked", "color": "red" },
        { "slug": "resolved", "label": "Resolved", "color": "green" }
    ]
}
```

## Predefined States

| Slug        | Label       | Color  | Description                             | Closed? |
| ----------- | ----------- | ------ | --------------------------------------- | ------- |
| open        | Open        | gray   | Ticket is open and awaiting action      | No      |
| in_progress | In Progress | blue   | Actively being worked on                | No      |
| testing     | Testing     | purple | In testing phase                        | No      |
| review      | Review      | purple | Under review                            | No      |
| waiting     | Waiting     | yellow | Waiting for external input              | No      |
| blocked     | Blocked     | red    | Blocked by dependency or issue          | No      |
| resolved    | Resolved    | green  | Issue has been resolved                 | No      |
| wontfix     | Won't Fix   | gray   | Will not be implemented (admin/PM only) | Yes     |
| closed      | Closed      | gray   | Completed and closed                    | Yes     |

## Frontend Integration

### Display Status Badge

```blade
@php
    $statusObj = $ticket->getCurrentStatusObject();
@endphp

@if($statusObj)
    <span class="{{ $statusObj->color_class }} px-3 py-1 rounded-full text-sm">
        {{ $statusObj->label }}
    </span>
@endif
```

### Status Dropdown in Forms

```blade
<select name="status" class="input">
    @foreach($workflowStates as $state)
        <option value="{{ $state->slug }}" {{ $ticket->status == $state->slug ? 'selected' : '' }}>
            {{ $state->label }}
        </option>
    @endforeach
</select>
```

### Display Status History

```blade
@include('tickets.partials.status-history', ['ticket' => $ticket])
```

## Migrations & Seeding

### Running Migrations

```bash
php artisan migrate
```

This creates:

- `workflow_states` table
- `workflow_state_permissions` table
- `ticket_status_history` table
- Updates `projects` table with `workflow_type`
- Updates `tickets` table (status enum → string, adds previous_status)

### Seeding Predefined States

```bash
php artisan db:seed --class=WorkflowStatesSeeder
```

This creates the 9 predefined states and sets default permissions.

### Migrating Legacy Statuses

If upgrading from the old enum system:

```bash
php artisan workflow:migrate-legacy-statuses
```

This command:

- Maps old enum values to new workflow state slugs
- Updates all existing tickets
- Verifies all states exist

## Best Practices

1. **Use Global Workflows** for most cases unless projects need drastically different workflows
2. **Keep Custom States Minimal** - too many states can confuse users
3. **Set Clear Permissions** - restrict sensitive states (like "wontfix") to appropriate roles
4. **Document State Meanings** - use descriptions to clarify what each state represents
5. **Order Logically** - order states in the typical flow sequence
6. **Use Color Consistently** - maintain color meanings across states (e.g., red for blocked, green for done)

## Troubleshooting

### "Invalid status" Validation Error

**Cause**: Ticket status doesn't match any workflow state slug for the project.

**Solution**:

1. Check project's `workflow_type` setting
2. Verify the status slug exists in available states
3. Run `workflow:migrate-legacy-statuses` if upgrading

### Permission Denied on Status Change

**Cause**: User's role doesn't have permission for the target state.

**Solution**:

1. Check state permissions in Admin > Workflows
2. Verify user's role assignment
3. Add role to state permissions or make state available to all

### Status History Not Recording

**Cause**: TicketObserver not registered or user context missing.

**Solution**:

1. Verify `Ticket::observe(TicketObserver::class)` in AppServiceProvider
2. Ensure status changes happen in authenticated context
3. Check if `Auth::id()` returns valid user ID

## Future Enhancements

Potential improvements for future versions:

- Workflow transition rules (define allowed state-to-state transitions)
- Automatic state transitions based on conditions
- Custom actions/hooks on state changes
- Workflow templates for quick project setup
- Visual workflow designer UI
- State-specific required fields
- SLA tracking per state

## Support

For issues or questions:

- Check GitHub Issues
- Review API documentation in `docs/api.md`
- Contact project maintainers
