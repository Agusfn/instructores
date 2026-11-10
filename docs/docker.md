# Docker

Single-container demo setup: Apache + PHP 7.4 + MariaDB (managed by supervisord). No external services needed.

## Build and run

```sh
docker compose up -d --build
# open http://localhost:8080
docker compose logs -f app
```

On first start the entrypoint initialises MariaDB, writes the Laravel `.env` from environment variables,
generates `APP_KEY` (persisted in the DB volume), then runs `php artisan migrate --force` and `db:seed --force`
(countries + admin user). Subsequent starts skip this.

## Configuration

Defaults are demo-safe (see `docker-compose.yml`). Override with environment variables, e.g.:

```sh
APP_URL=https://example.com DB_PASSWORD=secret docker compose up -d
```

Notable variables: `APP_URL`, `APP_KEY`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `MAIL_*`
(default `MAIL_DRIVER=log`: emails are written to the container log instead of being sent),
`MP_ACCESS_TOKEN`, `MP_PUBLIC_KEY`, `FACEBOOK_*`, `GOOGLE_*`, `ADMIN_NOTIF_MAIL`.
MercadoPago and social login use placeholders, so those flows will not work until real keys are provided.

## Seeded admin account

`AdminSeeder` creates the back-office admin: `admin@mail.com` / `instruc@catedral2209`.
Change it for anything other than a local demo.

## Seeded demo accounts

On first start the entrypoint also runs `DemoAccountsSeeder` (not part of `DatabaseSeeder`, so a
regular `db:seed` never creates them). Both have their email already verified:

| Account    | Email                  | Password   | Login URL           |
| ---------- | ---------------------- | ---------- | ------------------- |
| User       | `user@demo.test`       | `demo1234` | `/login`            |
| Instructor | `instructor@demo.test` | `demo1234` | `/instructor/login` |

The instructor is approved (with its service and wallet created), so its panel is fully usable.

## Volumes

- `dbdata` -> `/var/lib/mysql` (database, `APP_KEY`, first-run marker)
- `appstorage` -> `/var/www/app/storage/app` (uploaded files)

## Reset

```sh
docker compose down -v   # removes containers and volumes (all data and the generated APP_KEY)
```
