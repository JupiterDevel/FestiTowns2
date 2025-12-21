#!/bin/bash

# Script para corregir permisos de Laravel
# Este script cambia el propietario y permisos de storage y bootstrap/cache

PROJECT_DIR="/home/jupiter/Documentos/FestiTown/FestiTowns2/NewLaravelProject"

echo "Corrigiendo permisos de Laravel..."
echo ""

# Cambiar propietario de storage
echo "Cambiando propietario de storage..."
sudo chown -R jupiter:jupiter "$PROJECT_DIR/storage"
if [ $? -eq 0 ]; then
    echo "✓ Propietario de storage cambiado correctamente"
else
    echo "✗ Error al cambiar propietario de storage"
    exit 1
fi

# Cambiar propietario de bootstrap/cache
echo "Cambiando propietario de bootstrap/cache..."
sudo chown -R jupiter:jupiter "$PROJECT_DIR/bootstrap/cache"
if [ $? -eq 0 ]; then
    echo "✓ Propietario de bootstrap/cache cambiado correctamente"
else
    echo "✗ Error al cambiar propietario de bootstrap/cache"
    exit 1
fi

# Cambiar permisos de storage
echo "Ajustando permisos de storage..."
chmod -R 775 "$PROJECT_DIR/storage"
if [ $? -eq 0 ]; then
    echo "✓ Permisos de storage ajustados correctamente"
else
    echo "✗ Error al ajustar permisos de storage"
    exit 1
fi

# Cambiar permisos de bootstrap/cache
echo "Ajustando permisos de bootstrap/cache..."
chmod -R 775 "$PROJECT_DIR/bootstrap/cache"
if [ $? -eq 0 ]; then
    echo "✓ Permisos de bootstrap/cache ajustados correctamente"
else
    echo "✗ Error al ajustar permisos de bootstrap/cache"
    exit 1
fi

echo ""
echo "¡Permisos corregidos exitosamente!"
echo ""
echo "Verificando permisos..."
ls -la "$PROJECT_DIR/storage/framework/views/" | head -3



