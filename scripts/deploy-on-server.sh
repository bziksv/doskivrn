#!/usr/bin/env bash
# Деплой на Beget с сервера: cd ~ && bash scripts/deploy-on-server.sh
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"
BRANCH="${DEPLOY_BRANCH:-main}"

if [[ -f configure.php ]]; then
  cp -a configure.php /tmp/doskivrn_configure.php.bak
fi
if [[ -f .htaccess ]]; then
  cp -a .htaccess /tmp/doskivrn_htaccess.bak
fi

echo "==> git fetch origin $BRANCH"
git fetch origin "$BRANCH"
git merge --ff-only "origin/$BRANCH"

bash "$(dirname "$0")/post-deploy.sh"

echo "==> deploy complete"
