#!/usr/bin/env bash
# Проверка: все файлы для деплоя есть в репозитории.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

required=(
  .htaccess
  scripts/deploy-on-server.sh
  scripts/post-deploy.sh
  scripts/deploy-prod.sh
  scripts/ensure-htaccess-rules.sh
  scripts/install-legal-pages.php
  scripts/recompile-templates.php
  scripts/data/search_tables.sql
  classes/smarty/Smarty_Compiler.class.php
  search/index.php
  search/include/searchfuncs.php
  search/include/commonfuncs.php
  search/settings/database.php
  .local/legal-pages/doskivrn_politika_pd.html
  .local/legal-pages/doskivrn_politika_cookie.html
  .local/legal-pages/doskivrn_soglasie_pd.html
  .local/legal-pages/doskivrn_rekomendacii.html
  templates/default/footer.tpl
  templates/default/sidebar.tpl
  templates/default/head.tpl
  templates/default/pages.tpl
  css/pages/legal.css
  js/feedback-phone.js
  modules/module.pages.php
  modules/module.feedback.php
  lib.php
)

missing=0
tracked=0
for f in "${required[@]}"; do
  if [[ ! -f "$f" ]]; then
    echo "MISSING FILE: $f"
    missing=$((missing + 1))
    continue
  fi
  if git ls-files --error-unmatch "$f" >/dev/null 2>&1; then
    tracked=$((tracked + 1))
  else
    echo "NOT IN GIT: $f"
    missing=$((missing + 1))
  fi
done

if [[ "$missing" -gt 0 ]]; then
  echo "FAIL: $missing problem(s)"
  exit 1
fi

echo "OK: ${#required[@]} files on disk, $tracked tracked in git"
