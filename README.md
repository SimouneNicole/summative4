# Tasks for Today Management System — IT0049 TSA4 (Laravel)

A Laravel 13 task management application built for **IT0049 (Web System Technologies) — Technical Summative Assessment 4 (TSA4)**. Extending Simoune Nicole's TSA3 (`SimouneNicole/summative3`) **Liquid Glass** system, this version adds full validated task CRUD, soft-archiving via `is_archived`, **Laravel Breeze** authentication protecting task management actions, and automatic email notifications (`TaskCompletedMail`) dispatched via Laravel's `Mail` facade when a task is marked completed.

- **Developer:** Simoune Nicole
- **GitHub Repository:** [SimouneNicole/summative4](https://github.com/SimouneNicole/summative4)
- **Live Deployment:** [https://it0049-summative4.infinityfree.io](https://it0049-summative4.infinityfree.io)

---

## System Requirements

- **PHP**: 8.3 or higher (`pdo_sqlite`, `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`)
- **Composer**: 2.0 or higher
- **Node.js & npm**: 18+ (for optional Breeze/Vite assets)
- **Database**: SQLite 3 (local development/testing) or MySQL / MariaDB (production)

---

## Local Setup & Running

1. **Clone the repository**:
   ```bash
   git clone https://github.com/SimouneNicole/summative4.git
   cd summative4
   ```

2. **Install dependencies**:
   ```bash
   composer install
   npm install && npm run build
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

## Authentication & Seeded Demo Credentials

Laravel Breeze provides session authentication (`/login`, `/register`, `/logout`, password reset, and `/account/profile`).

- **Publicly Viewable Routes:** `/`, `/tasks`, `/profile`, `/about`
- **Protected Routes (`auth` middleware):** `/tasks/create`, `POST /tasks`, `/tasks/{task}/edit`, `PUT /tasks/{task}`, `DELETE /tasks/{task}`, `/account/profile`

### Seeded Demo Account
- **Email:** `katarina.ivanov@gmail.com`
- **Username:** `Kat.1na`
- **Password:** `password`

You may also register a brand-new user account via `/register`.

---

## Mail Configuration (`TaskCompletedMail`)

When an authenticated user updates a task and changes its status from `pending` or `ongoing` to `completed`, `TaskController@update` sends [`App\Mail\TaskCompletedMail`](app/Mail/TaskCompletedMail.php) (rendered with [`resources/views/emails/task-completed.blade.php`](resources/views/emails/task-completed.blade.php)) via `Mail::to(...)`. Updating an already-completed task will **not** resend the email.

Configure mail settings in `.env`:

```dotenv
# Local / Shared Hosting Log Driver (writes rendered emails to storage/logs/laravel.log)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
TASK_NOTIFICATION_EMAIL="katarina.ivanov@gmail.com"
```

To test with an SMTP sandbox (e.g., Mailpit, Mailtrap, or Gmail SMTP), set:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
TASK_NOTIFICATION_EMAIL="katarina.ivanov@gmail.com"
```

---

## Application Routes

| Method | URI | Middleware | Controller Action | Purpose |
| :--- | :--- | :--- | :--- | :--- |
| `GET` | `/` | Public | `WelcomeController@index` | Non-archived tasks scheduled for today |
| `GET` | `/tasks` | Public | `TaskController@index` | All non-archived tasks ordered by `task_date` |
| `GET` | `/profile` | Public | `ProfileController@index` | Public user profile summary |
| `GET` | `/about` | Public | `AboutController@index` | Application & architecture overview |
| `GET` | `/tasks/create` | `auth` | `TaskController@create` | Create task form |
| `POST` | `/tasks` | `auth` | `TaskController@store` | Validate (`SaveTaskRequest`) & insert task |
| `GET` | `/tasks/{task}/edit` | `auth` | `TaskController@edit` | Edit pre-filled task form |
| `PUT` | `/tasks/{task}` | `auth` | `TaskController@update` | Validate, update, & mail on transition to `completed` |
| `DELETE` | `/tasks/{task}` | `auth` | `TaskController@destroy` | Soft-archive task (`is_archived = true`) |
| `GET/PATCH/DELETE` | `/account/profile` | `auth` | `ProfileController@edit/update/destroy` | Breeze account settings |

---

## Automated Testing

Run the full PHPUnit test suite (TSA3 foundations, Breeze auth/profile, TSA4 CRUD, soft-archive, validation/`old()`, and `Mail::fake()` notification tests):

```bash
php artisan test
```

---

## InfinityFree Deployment

1. Copy `env.production.example` to `env.production` (gitignored) and set `APP_KEY`, `APP_URL`, `DB_*`, `MAIL_*`, and `TASK_NOTIFICATION_EMAIL`.
2. Run `./build-infinityfree-zip.sh` (or `./deploy-infinityfree.sh` to upload via FTP).
3. Open `extract.php` on the live domain to unpack assets, set permissions, and provision MySQL from `database/schema_export.sql` (with automatic SQLite fallback).

---

## License

Licensed under the [MIT License](LICENSE).
