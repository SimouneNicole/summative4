#!/usr/bin/env bash
set -euo pipefail

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
TMP_DIR="$(mktemp -d)"
trap 'rm -rf "$TMP_DIR"' EXIT

OUTPUT_ZIP="$DIR/summative4-infinityfree.zip"
ENV_SOURCE="${ENV_SOURCE:-$DIR/env.production}"

if [ ! -f "$ENV_SOURCE" ]; then
    echo "Error: Production environment file not found at $ENV_SOURCE" >&2
    echo "Copy env.production.example to env.production and set APP_KEY and DB_PASSWORD first." >&2
    exit 1
fi

echo "==> Packaging lean production build for InfinityFree (it0049-tsa4)..."
rm -f "$OUTPUT_ZIP"

rsync -av \
  --exclude=".*" \
  --exclude="vendor" \
  --exclude="node_modules" \
  --exclude="tests" \
  --exclude="scripts" \
  --exclude="storage/framework/views/*.php" \
  --exclude="storage/framework/sessions/*" \
  --exclude="storage/logs/*.log" \
  --exclude="*.zip" \
  --exclude="env.production" \
  --exclude="env.production.example" \
  --exclude="build-infinityfree-zip.sh" \
  --exclude="deploy-infinityfree.sh" \
  --exclude="extract.php" \
  --exclude="AGENTS.md" \
  --exclude="CLAUDE.md" \
  --exclude="PRODUCT.md" \
  --exclude="DESIGN.md" \
  "$DIR/" "$TMP_DIR/" > /dev/null

# Copy shared hosting rewrite and entry shims
cp "$DIR/.htaccess" "$TMP_DIR/.htaccess"
cp "$DIR/index.php" "$TMP_DIR/index.php"
cp "$DIR/public/.htaccess" "$TMP_DIR/public/.htaccess"

# Mirror CSS and favicon to root for shared hosts without public/ rewrite support
if [ -d "$DIR/public/css" ]; then
    mkdir -p "$TMP_DIR/css"
    cp -r "$DIR/public/css/"* "$TMP_DIR/css/"
fi
[ -f "$DIR/public/favicon.svg" ] && cp "$DIR/public/favicon.svg" "$TMP_DIR/favicon.svg"

# Bundle gitignored production .env
cp "$ENV_SOURCE" "$TMP_DIR/.env"

# Ensure schema export, pre-seeded SQLite fallback, and writable storage directories exist
mkdir -p "$TMP_DIR/database"
[ -f "$DIR/database/schema_export.sql" ] && cp "$DIR/database/schema_export.sql" "$TMP_DIR/database/schema_export.sql"
if [ -f "$DIR/database/database.sqlite" ]; then
    cp "$DIR/database/database.sqlite" "$TMP_DIR/database/database.sqlite"
else
    touch "$TMP_DIR/database/database.sqlite"
fi

mkdir -p "$TMP_DIR/storage/app/public" \
         "$TMP_DIR/storage/framework/cache/data" \
         "$TMP_DIR/storage/framework/sessions" \
         "$TMP_DIR/storage/framework/views" \
         "$TMP_DIR/storage/logs" \
         "$TMP_DIR/bootstrap/cache"

cd "$TMP_DIR"
composer install --no-dev --optimize-autoloader --no-interaction --quiet

zip -r "$OUTPUT_ZIP" . -x "*.DS_Store" > /dev/null

echo "==> Verifying package contents..."
ZIP_LISTING="$(unzip -l "$OUTPUT_ZIP")"
for req in "public/index.php" "index.php" ".htaccess" "public/.htaccess" "artisan" "vendor/autoload.php" "public/css/style.css" "css/style.css" "public/favicon.svg" "favicon.svg" ".env" "database/schema_export.sql" "database/database.sqlite"; do
    if ! grep -Fq "$req" <<< "$ZIP_LISTING"; then
        echo "Error: Required file $req is missing from $OUTPUT_ZIP" >&2
        exit 1
    fi
done

echo "==> Build successful: $OUTPUT_ZIP"
ls -lh "$OUTPUT_ZIP"
