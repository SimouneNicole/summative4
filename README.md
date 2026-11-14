# Tasks for Today Management System — IT0049 TSA3 (Laravel)

A Laravel 13 task management application built for **IT0049 (Web System Technologies) — Technical Summative Assessment 3 (TSA3)**. It rebuilds the CodeIgniter 4 TSA1 Tasks for Today Management System (`SimouneNicole/summative1`) with Laravel schema migrations, seeders, Eloquent ORM local query scopes, and Blade views styled in the **Liquid Glass** interface.

- **Developer:** Simoune Nicole
- **GitHub Repository:** [SimouneNicole/summative3](https://github.com/SimouneNicole/summative3)
- **Live Deployment:** [https://it0049-tsa3.infinityfreeapp.com](https://it0049-tsa3.infinityfreeapp.com)

---

## System Requirements

- **PHP**: 8.3 or higher (`pdo_sqlite`, `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`)
- **Composer**: 2.0 or higher
- **Database**: SQLite 3 (local development/testing) or MySQL / MariaDB (production)

---

## Local Setup & Running

1. **Clone the repository**:
   ```bash
   git clone https://github.com/SimouneNicole/summative3.git
   cd summative3
   ```

2. **Install Composer dependencies**:
   ```bash
   composer install
   ```

3. **Configure environment and key**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Create SQLite database, run migrations, and seed data**:
   ```bash
   touch database/database.sqlite
   php artisan migrate:fresh --seed
   ```
   *(For MySQL, update `DB_CONNECTION=mysql` and database credentials in `.env`, or import `database/schema_export.sql` into phpMyAdmin.)*

5. **Run the development server**:
   ```bash
   php artisan serve --port 8080
   ```
   Open [http://localhost:8080](http://localhost:8080) in your browser.

---

## Application Routes

| Method | URI | Controller Action | Eloquent Query | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/` | `WelcomeController@index` | `Task::forToday()->orderBy('id')->get()` | Displays only tasks scheduled for today's date |
| `GET` | `/tasks` | `TaskController@index` | `Task::orderedByDate()->get()` | Displays all tasks ordered chronologically by `task_date` |
| `GET` | `/profile` | `ProfileController@index` | `User::query()->first()` | Displays the single user profile record |
| `GET` | `/about` | `AboutController@index` | Static view | Identifies Simoune Nicole as developer and summarizes architecture |

---

## Database Schema, Models & Seed Data

### Migrations (`database/migrations/`)
- `users` (`0001_01_01_000000_create_users_table.php`):
  - `id` (`BIGINT UNSIGNED PK`)
  - `username` (`VARCHAR(50) UNIQUE`)
  - `full_name` (`VARCHAR(100)`)
  - `email` (`VARCHAR(100)`)
  - `created_at`, `updated_at` (`TIMESTAMPS`)
- `tasks` (`2026_11_14_000001_create_tasks_table.php`):
  - `id` (`BIGINT UNSIGNED PK`)
  - `title` (`VARCHAR(255)`)
  - `status` (`VARCHAR(20) DEFAULT 'pending'`, cast via `App\Enums\TaskStatus`: `pending`, `ongoing`, `completed`)
  - `task_date` (`DATE INDEX`)
  - `created_at`, `updated_at` (`TIMESTAMPS`)

### Eloquent Models (`app/Models/`)
- `App\Models\Task`: casts `task_date` (`date`) and `status` (`TaskStatus` enum) and defines Laravel 13 `#[Scope]` local scopes `forToday()` and `orderedByDate()`.
- `App\Models\User`: maps the `users` table (`username`, `full_name`, `email`).

### Seeders (`database/seeders/`)
- `UserSeeder`: seeds 1 canonical user (`Kat.1na`, `Katarina Ivanov`, `katarina.ivanov@gmail.com`).
- `TaskSeeder`: seeds 9 tasks spanning 4 distinct dates (yesterday, today, tomorrow, and +2 days), preserving all original TSA1 titles and workflow states.
- `DatabaseSeeder`: executes `[UserSeeder::class, TaskSeeder::class]`.

---

## Automated Testing

Run the PHPUnit test suite against an isolated in-memory SQLite database:

```bash
php artisan test
```

The test suite verifies seeder record counts (`1` user, `>= 8` tasks across `>= 3` dates), Eloquent `forToday` and `orderedByDate` scopes, database schema constraints (`status` default `'pending'`, unique `username`), and HTTP 200 content assertions for `/`, `/tasks`, `/profile`, and `/about`.

---

## InfinityFree Deployment

1. Copy `env.production.example` to `env.production` and set `APP_KEY` and `DB_PASSWORD`.
2. Run `./build-infinityfree-zip.sh` (or `./deploy-infinityfree.sh` to upload via FTP).
3. Open `extract.php` on the live domain to unpack assets, configure storage permissions, and provision MySQL from `database/schema_export.sql`.

---

## License

Licensed under the [MIT License](LICENSE).
