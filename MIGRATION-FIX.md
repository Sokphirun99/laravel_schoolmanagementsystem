# Migration Issue Fix

## Problem

The Laravel school management system had a migration issue where the `assignments` table migration was trying to create a foreign key constraint to the `courses` table, but the `courses` table hadn't been created yet.

Specifically:

- The `2025_06_23_073954_create_assignments_table.php` migration was trying to create the `assignments` table with a foreign key to `courses`.
- However, the `courses` table was being created in `2026_05_22_000000_create_gradebook_tables.php` migration, which would run *after* the assignments migration due to its later timestamp.
- This caused the migration to fail with an error about the `courses` table not existing.
- Additionally, there was a duplicate migration file `2025_06_24_164039_create_courses_table.php` that was also causing conflicts.

## Solution

The solution involved four key steps:

1. Renamed the `2026_05_22_000000_create_gradebook_tables.php` migration to `2024_05_22_000000_create_gradebook_tables.php` to ensure it runs *before* any migrations that depend on the tables it creates.

2. Modified the gradebook tables migration to check if tables already exist before creating them using `Schema::hasTable()`, to prevent errors when tables were already created in earlier migration attempts.

3. Removed duplicate migrations:
   - Deleted `2025_06_23_073954_create_assignments_table.php` since the `assignments` table is already created in the gradebook migration
   - Deleted the duplicate courses table migration `2025_06_24_164039_create_courses_table.php` that was created during troubleshooting

4. Ensured all migrations were run in the correct order, confirmed by checking the migration status.

This ensures the correct sequence of migrations:
1. First, the gradebook tables migration runs, creating the `courses` table
2. Then, any migrations that need the `courses` table can safely reference it

## Database Structure

The gradebook migration sets up these related tables:

- `courses`: Stores course information and is linked to a teacher
- `assignments`: Stores assignment details and is linked to a course 
- `grades`: Stores student grades for assignments
- `course_enrollments`: Tracks which students are enrolled in which courses

## Running Migrations

To apply the migrations, use:

```bash
php artisan migrate
```

If you ever need to start fresh:

```bash
php artisan migrate:fresh
```

## Note on Docker

This project is configured to use Docker for the database. For local development without Docker:

1. Update your `.env` file to use localhost:
   ```
   DB_HOST=127.0.0.1
   ```

2. Make sure you have a local MySQL server running.
