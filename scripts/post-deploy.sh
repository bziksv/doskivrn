#!/usr/bin/env bash
# После git pull на Beget: restore конфигов, кэш, legal pages, htaccess.
# Ожидает бэкапы в /tmp/doskivrn_configure.php.bak и /tmp/doskivrn_htaccess.bak
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

if [[ -f /tmp/doskivrn_configure.php.bak ]]; then
  cp -a /tmp/doskivrn_configure.php.bak configure.php
  echo "restored configure.php"
fi
if [[ -f /tmp/doskivrn_htaccess.bak ]]; then
  cp -a /tmp/doskivrn_htaccess.bak .htaccess
  echo "restored .htaccess"
fi

rm -rf templates_c/* css/compressed/* 2>/dev/null || true
echo "cache cleared"

if [[ -f scripts/install-legal-pages.php ]]; then
  php scripts/install-legal-pages.php
fi

if [[ -f scripts/ensure-htaccess-rules.sh ]]; then
  bash scripts/ensure-htaccess-rules.sh .htaccess
fi

git log -1 --oneline
