#!/usr/bin/env bash
# ====================================================================
# IT0049 TSA4 - Automated FTP Deployment to InfinityFree
# Simoune Nicole - it0049-tsa4.infinityfreeapp.com
# ====================================================================

set -euo pipefail

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$DIR"

python3 "$DIR/scripts/deploy_infinityfree.py"
