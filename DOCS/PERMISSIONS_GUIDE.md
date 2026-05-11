# Permission Naming Standards

> [!NOTE]
> For a high-level overview of the project structure, see [ARCHITECTURE.md](ARCHITECTURE.md).

In this project, we follow a strict **"Hyphenated Slug"** standard for all permission names. This ensures consistency across the database, code, and UI.

## The Rule
- **Standard Format**: `action-module` (e.g., `view-employees`, `view-timesheet`, `manage-attendance`).
- **NO SPACES**: Spaces are strictly forbidden. Use `view-timesheet`, NOT `view timesheet`.
- **NO UNDERSCORES**: Use hyphens (`-`), NOT underscores (`_`).
- **LOWERCASE**: All permission names must be lowercase.

## Enforcement
The standardization is enforced at the **Model level** in `app/Models/Permission.php`.

```php
// app/Models/Permission.php
public function setNameAttribute($value)
{
    $this->attributes['name'] = str_replace([' ', '_'], '-', strtolower($value));
}
```

This means even if you accidentally pass a string with spaces to `Permission::create()`, it will automatically be converted to a slug before being saved to the database.

## Usage in Code

### Checking Permissions
Always use the hyphenated slug:
```php
if ($user->can('view-employees')) { ... }
```

### Displaying in UI (Blade)
To display a user-friendly label in the UI, use `str_replace('-', ' ', $permission->name)` and `ucfirst()` or `capitalize` class:
```blade
<span class="capitalize">
    {{ str_replace('-', ' ', $permission->name) }}
</span>
```

## Admin UI
In the Role Management UI (`RoleController`), permissions are automatically grouped by the last word of the slug. For example, `view-employees` and `edit-employees` will both be grouped under **"Employees"**.
