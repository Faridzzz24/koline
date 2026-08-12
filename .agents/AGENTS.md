# Agent Behavior & Project Guidelines

## 🚨 Database Integrity & Persistence Rules (CRITICAL)

1. **NEVER Reset or Re-seed Database Automatically**:
   - DO NOT run `php artisan migrate:fresh`, `php artisan db:seed`, `php artisan migrate:reset`, or `php artisan migrate:refresh` unless explicitly requested by the USER in their prompt.
   - DO NOT execute `git checkout database/database.sqlite`, `git restore database/database.sqlite`, or delete `database/database.sqlite`.

2. **Preserve User Account Modifications**:
   - Accounts (patients, doctors, admins) added or deleted by the user in the UI must remain persistent in `database/database.sqlite`.
   - Never wipe or revert user-created data.
