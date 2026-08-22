#!/usr/bin/env bash
# Deploy doskivrn.ru: git push → git pull on Beget.
set -euo pipefail

cd "$(dirname "$0")/.."
SITE_ROOT="$(pwd)"
SSH_HOST="${DOSKIVRN_SSH_HOST:-doskivrn_primeseo@kirk}"
# На Prime/kirk домашняя директория = public_html (не ~/doskivrn.ru/public_html).
PROD_PATH="${DOSKIVRN_PROD_PATH:-.}"
REMOTE=origin
BRANCH=main
REPO_URL="https://github.com/bziksv/doskivrn.git"

echo "==> Push local commits"
git status -sb | head -5
git push "$REMOTE" "$BRANCH"

echo "==> Pull on production (${SSH_HOST})"
ssh -o BatchMode=yes "$SSH_HOST" "bash -s" -- "$PROD_PATH" "$REMOTE" "$BRANCH" "$REPO_URL" <<'REMOTE'
set -euo pipefail
PROD_PATH="$1"
REMOTE="$2"
BRANCH="$3"
REPO_URL="$4"

cd ~/"$PROD_PATH"
git config --global --add safe.directory "$(pwd)"

if [[ -f configure.php ]]; then
  cp -a configure.php /tmp/doskivrn_configure.php.bak
fi
if [[ -f .htaccess ]]; then
  cp -a .htaccess /tmp/doskivrn_htaccess.bak
fi

if [[ ! -d .git ]]; then
  git init -b "$BRANCH"
  git remote add "$REMOTE" "$REPO_URL" 2>/dev/null || git remote set-url "$REMOTE" "$REPO_URL"
  GIT_TERMINAL_PROMPT=0 git fetch "$REMOTE" "$BRANCH"
  git checkout -B "$BRANCH" "$REMOTE/$BRANCH" -f
else
  git remote set-url "$REMOTE" "$REPO_URL" 2>/dev/null || true
  GIT_TERMINAL_PROMPT=0 git fetch "$REMOTE" "$BRANCH"
  if git rev-parse --verify "$BRANCH" >/dev/null 2>&1 && git merge-base --is-ancestor HEAD "$REMOTE/$BRANCH" 2>/dev/null; then
    git merge --ff-only "$REMOTE/$BRANCH"
  else
    git checkout -B "$BRANCH" "$REMOTE/$BRANCH" 2>/dev/null || git branch -M "$BRANCH" 2>/dev/null || true
    git reset --hard "$REMOTE/$BRANCH"
  fi
fi

if [[ -f /tmp/doskivrn_configure.php.bak ]]; then
  cp -a /tmp/doskivrn_configure.php.bak configure.php
  echo "restored configure.php"
fi
if [[ -f /tmp/doskivrn_htaccess.bak ]]; then
  cp -a /tmp/doskivrn_htaccess.bak .htaccess
  echo "restored .htaccess"
fi

rm -rf templates_c/* css/compressed/* 2>/dev/null || true
git log -1 --oneline
echo "cache_cleared"
REMOTE

echo "==> Verify"
curl -sS -o /dev/null -w 'home %{http_code}\n' --max-time 25 https://www.doskivrn.ru/
curl -sS -o /dev/null -w 'articles %{http_code}\n' --max-time 25 https://www.doskivrn.ru/pages/articles/
echo "==> Done"
