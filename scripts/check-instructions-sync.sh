#!/usr/bin/env bash
# Checks that the rule sections of AGENTS.md and .github/copilot-instructions.md are identical.
# Copyright (c) 2026 die.internauten.ch GmbH
# License: MIT
set -euo pipefail
cd "$(dirname "$0")/.."

rules() { sed -n '/^## Project context/,$p' "$1"; }

if diff <(rules AGENTS.md) <(rules .github/copilot-instructions.md); then
  echo "OK: instruction files are in sync."
else
  echo "ERROR: AGENTS.md and .github/copilot-instructions.md differ." >&2
  exit 1
fi
