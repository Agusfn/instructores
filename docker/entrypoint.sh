#!/bin/sh
set -e
APP=/var/www/app
DATADIR=/var/lib/mysql
SOCK=/run/mysqld/mysqld.sock
KEYFILE="$DATADIR/.laravel-app-key"
MARKER="$DATADIR/.laravel-initialized"

: "${DB_DATABASE:?DB_DATABASE is required}"
: "${DB_USERNAME:?DB_USERNAME is required}"
: "${DB_PASSWORD:?DB_PASSWORD is required}"
case "$DB_DATABASE" in
  *[!A-Za-z0-9_]*) echo "[entrypoint] DB_DATABASE must match [A-Za-z0-9_]+" >&2; exit 1 ;;
esac

# Escape backslashes and single quotes for SQL string literals
sqlesc() { printf '%s' "$1" | sed -e 's/\\/\\\\/g' -e "s/'/\\\\'/g"; }
# Escape a value for a double-quoted .env entry
envesc() { printf '%s' "$1" | sed -e 's/\\/\\\\/g' -e 's/"/\\"/g' -e 's/\$/\\$/g'; }

# ---- MariaDB: data directory ----
mkdir -p /run/mysqld
chown mysql:mysql /run/mysqld "$DATADIR"

start_tmp_db() {
  mariadbd --user=mysql --skip-networking --socket="$SOCK" &
  DB_PID=$!
  i=0
  until mariadb-admin --socket="$SOCK" -uroot ping >/dev/null 2>&1; do
    i=$((i+1))
    [ "$i" -gt 90 ] && { echo "[entrypoint] mariadbd did not start" >&2; exit 1; }
    kill -0 "$DB_PID" 2>/dev/null || { echo "[entrypoint] mariadbd died" >&2; exit 1; }
    sleep 1
  done
}

if [ ! -d "$DATADIR/mysql" ]; then
  echo "[entrypoint] Initialising MariaDB data directory"
  mariadb-install-db --user=mysql --datadir="$DATADIR" --skip-test-db >/dev/null
fi

# ---- Laravel APP_KEY: generate once and persist in the DB volume ----
if [ -z "$APP_KEY" ]; then
  if [ ! -s "$KEYFILE" ]; then
    echo "base64:$(head -c 32 /dev/urandom | base64)" > "$KEYFILE"
    chmod 600 "$KEYFILE"
  fi
  APP_KEY=$(cat "$KEYFILE")
fi

# ---- Laravel .env from environment variables ----
{
  echo "APP_NAME=\"$(envesc "${APP_NAME:-Instructores}")\""
  echo "APP_ENV=${APP_ENV:-production}"
  echo "APP_KEY=$APP_KEY"
  echo "APP_DEBUG=${APP_DEBUG:-false}"
  echo "APP_URL=${APP_URL:-http://localhost:8080}"
  echo "LOG_CHANNEL=${LOG_CHANNEL:-stderr}"
  echo "DB_CONNECTION=mysql"
  echo "DB_HOST=${DB_HOST:-127.0.0.1}"
  echo "DB_PORT=${DB_PORT:-3306}"
  echo "DB_DATABASE=$DB_DATABASE"
  echo "DB_USERNAME=$DB_USERNAME"
  echo "DB_PASSWORD=\"$(envesc "$DB_PASSWORD")\""
  echo "BROADCAST_DRIVER=log"
  echo "CACHE_DRIVER=file"
  echo "SESSION_DRIVER=file"
  echo "QUEUE_CONNECTION=sync"
  echo "FILESYSTEM_DRIVER=${FILESYSTEM_DRIVER:-public}"
  echo "MAIL_DRIVER=${MAIL_DRIVER:-log}"
  echo "MAIL_HOST=${MAIL_HOST:-localhost}"
  echo "MAIL_PORT=${MAIL_PORT:-25}"
  echo "MAIL_USERNAME=${MAIL_USERNAME:-null}"
  echo "MAIL_PASSWORD=${MAIL_PASSWORD:-null}"
  echo "MAIL_ENCRYPTION=${MAIL_ENCRYPTION:-null}"
  echo "MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS:-no-reply@example.com}"
  echo "MAIL_FROM_NAME=\"$(envesc "${MAIL_FROM_NAME:-Instructores}")\""
  echo "ADMIN_NOTIF_MAIL=${ADMIN_NOTIF_MAIL:-admin@example.com}"
  echo "ADMIN_NOTIF_NAME=\"$(envesc "${ADMIN_NOTIF_NAME:-Admin}")\""
  echo "MP_ACCESS_TOKEN=${MP_ACCESS_TOKEN:-TEST-placeholder-access-token}"
  echo "MP_PUBLIC_KEY=${MP_PUBLIC_KEY:-TEST-placeholder-public-key}"
  echo "MP_WEBHOOK_URL=${MP_WEBHOOK_URL:-http://localhost:8080/mp/webhook}"
  echo "FACEBOOK_APP_ID=${FACEBOOK_APP_ID:-placeholder}"
  echo "FACEBOOK_APP_SECRET=${FACEBOOK_APP_SECRET:-placeholder}"
  echo "FACEBOOK_REDIRECT=${FACEBOOK_REDIRECT:-http://localhost:8080/login/facebook/callback}"
  echo "FACEBOOK_REDIRECT_INSTRUCTOR=${FACEBOOK_REDIRECT_INSTRUCTOR:-http://localhost:8080/instructor/login/facebook/callback}"
  echo "GOOGLE_APP_ID=${GOOGLE_APP_ID:-placeholder}"
  echo "GOOGLE_APP_SECRET=${GOOGLE_APP_SECRET:-placeholder}"
  echo "GOOGLE_REDIRECT=${GOOGLE_REDIRECT:-http://localhost:8080/login/google/callback}"
  echo "GOOGLE_REDIRECT_INSTRUCTOR=${GOOGLE_REDIRECT_INSTRUCTOR:-http://localhost:8080/instructor/login/google/callback}"
} > "$APP/.env"
chown www-data:www-data "$APP/.env"
chmod 640 "$APP/.env"

# ---- Writable dirs (a volume-backed storage/app may arrive empty or root-owned) ----
mkdir -p "$APP/storage/app/public" "$APP/storage/framework/cache/data" \
  "$APP/storage/framework/sessions" "$APP/storage/framework/views" "$APP/storage/logs" "$APP/bootstrap/cache"
[ -f "$APP/storage/settings.json" ] || echo '{}' > "$APP/storage/settings.json"
chown -R www-data:www-data "$APP/storage" "$APP/bootstrap/cache"

# ---- Create DB/user, migrate and seed (first run only) ----
if [ ! -f "$MARKER" ]; then
  echo "[entrypoint] First run: creating database and seeding"
  start_tmp_db
  P=$(sqlesc "$DB_PASSWORD"); U=$(sqlesc "$DB_USERNAME")
  mariadb --socket="$SOCK" -uroot <<SQL
CREATE DATABASE IF NOT EXISTS \`$DB_DATABASE\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$U'@'127.0.0.1' IDENTIFIED BY '$P';
CREATE USER IF NOT EXISTS '$U'@'localhost' IDENTIFIED BY '$P';
GRANT ALL PRIVILEGES ON \`$DB_DATABASE\`.* TO '$U'@'127.0.0.1';
GRANT ALL PRIVILEGES ON \`$DB_DATABASE\`.* TO '$U'@'localhost';
FLUSH PRIVILEGES;
SQL
  cd "$APP"
  # The temp server has networking disabled: connect through the socket
  DB_HOST=localhost DB_SOCKET="$SOCK" su -s /bin/sh www-data -p -c "php artisan migrate --force"
  DB_HOST=localhost DB_SOCKET="$SOCK" su -s /bin/sh www-data -p -c "php artisan db:seed --force"
  DB_HOST=localhost DB_SOCKET="$SOCK" su -s /bin/sh www-data -p -c "php artisan db:seed --class=DemoAccountsSeeder --force"
  touch "$MARKER"
  mariadb-admin --socket="$SOCK" -uroot shutdown
  wait "$DB_PID" || true
  echo "[entrypoint] First-run initialisation done"
fi

exec "$@"
