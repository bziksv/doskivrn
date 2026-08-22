#!/usr/bin/env bash
# Одноразовая настройка SSH для деплоя doskivrn.ru на Beget.
set -euo pipefail

PUBKEY_FILE="${HOME}/.ssh/id_ed25519.pub"
HOST="doskivrn_primeseo@kirk"

if [[ ! -f "$PUBKEY_FILE" ]]; then
  echo "Создайте ключ: ssh-keygen -t ed25519 -f ~/.ssh/id_ed25519 -N \"\" -C cursor-deploy-doskivrn"
  exit 1
fi

cat <<EOF
Добавьте публичный ключ в Beget → SSH-доступ для ${HOST}:

$(cat "$PUBKEY_FILE")

Проверка:
  ssh ${HOST} 'pwd && ls -la'

Деплой:
  ./scripts/deploy-prod.sh
EOF
