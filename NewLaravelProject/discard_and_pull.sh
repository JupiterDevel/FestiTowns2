#!/bin/bash

# Script para descartar cambios locales y hacer git pull

echo "Descartando cambios locales en archivos modificados..."
git restore app/Http/Controllers/VoteController.php
git restore package-lock.json
git restore resources/views/festivities/most-voted.blade.php
git restore resources/views/layouts/app.blade.php
git restore storage/app/.gitignore
git restore storage/app/private/.gitignore
git restore storage/app/public/.gitignore
git restore storage/framework/.gitignore
git restore storage/framework/cache/.gitignore
git restore storage/framework/cache/data/.gitignore
git restore storage/framework/sessions/.gitignore
git restore storage/framework/testing/.gitignore
git restore storage/framework/views/.gitignore
git restore storage/logs/.gitignore

echo "Eliminando archivos sin seguimiento que están en conflicto..."
rm -f config/autonomous_communities.php
rm -f resources/views/festivities/partials/festivity-card.blade.php

echo "Ejecutando git pull..."
git pull

