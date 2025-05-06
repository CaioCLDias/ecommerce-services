#!/bin/bash

# Espera o MySQL estar disponível
echo "⏳ Aguardando banco em $DB_HOST:$DB_PORT..."
until nc -z "$DB_HOST" "$DB_PORT"; do
  sleep 2
done

echo "✅ Banco disponível! Rodando migrations + seeders..."
php artisan migrate --seed --force

echo "🚀 Iniciando PHP-FPM..."
exec "$@"
