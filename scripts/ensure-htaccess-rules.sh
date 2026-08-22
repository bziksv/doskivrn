#!/usr/bin/env bash
# Добавляет критичные RewriteRule, если их нет (после restore старого .htaccess на проде).
set -euo pipefail

HTACCESS="${1:-.htaccess}"
if [[ ! -f "$HTACCESS" ]]; then
  echo "htaccess: file not found, skip"
  exit 0
fi

patch_rule() {
  local marker="$1"
  local rule="$2"
  if grep -qF "$marker" "$HTACCESS"; then
    echo "htaccess: OK $marker"
    return 0
  fi
  if grep -q '^RewriteEngine on' "$HTACCESS"; then
    sed -i.bak "/^RewriteEngine on/a\\
$rule
" "$HTACCESS"
    rm -f "${HTACCESS}.bak"
    echo "htaccess: added $marker"
  else
    echo "htaccess: WARN no RewriteEngine on, cannot add $marker"
    return 1
  fi
}

patch_rule 'search/index.php' 'RewriteRule ^search/?$ search/index.php [QSA,L]'
patch_rule 'admin/index.php' 'RewriteRule ^admin/?$ admin/index.php [L]'
