#!/bin/bash
# MutedBoard migration tool for Docker Compose environments
# Runs all SQL files in migrations/ against the MySQL container

# Set MySQL container service name (as in docker-compose.yml)
MYSQL_SERVICE="db"

# Load DB config from config/database.php
DB_CONFIG="$(dirname "$0")/../config/database.php"
MIGRATIONS_DIR="$(dirname "$0")/../migrations"

DB_HOST=$(grep "'host'" "$DB_CONFIG" | awk -F"'" '{print $4}')
DB_NAME=$(grep "'dbname'" "$DB_CONFIG" | awk -F"'" '{print $4}')
DB_USER=$(grep "'username'" "$DB_CONFIG" | awk -F"'" '{print $4}')
DB_PASS=$(grep "'password'" "$DB_CONFIG" | awk -F"'" '{print $4}')

if [ -z "$DB_HOST" ] || [ -z "$DB_NAME" ] || [ -z "$DB_USER" ]; then
  echo "Could not parse database config."
  exit 1
fi

# Run migrations inside the MySQL container using docker compose exec
for sqlfile in "$MIGRATIONS_DIR"/*.sql; do
  [ -e "$sqlfile" ] || continue
  echo "Applying migration: $sqlfile"
  docker compose exec -T "$MYSQL_SERVICE" mysql -h "$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$sqlfile"
  if [ $? -eq 0 ]; then
    echo "✓ $sqlfile applied successfully."
  else
    echo "✗ Error applying $sqlfile"
    exit 1
  fi
  echo "---"
done
echo "All migrations applied."
