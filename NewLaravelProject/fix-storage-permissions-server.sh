#!/bin/bash
# Script para corregir permisos de storage en el SERVIDOR (ejecutar donde está desplegada la app)
# El usuario del servidor web (ej. webuser o www-data) debe poder escribir en storage y bootstrap/cache.
#
# Uso en el servidor:
#   chmod +x fix-storage-permissions-server.sh
#   sudo ./fix-storage-permissions-server.sh

# Ruta del proyecto EN EL SERVIDOR (ajustar si es distinta)
PROJECT_DIR="${1:-/home/webuser/FestiTowns2/NewLaravelProject}"

# Usuario con el que corre el servidor web (nginx/apache/php-fpm). Ajustar si en tu servidor es otro (ej. www-data)
WEB_USER="${2:-webuser}"
# Grupo del usuario web (suele ser el mismo o www-data)
WEB_GROUP="${WEB_USER}"

if [ ! -d "$PROJECT_DIR" ]; then
    echo "Error: No existe el directorio del proyecto: $PROJECT_DIR"
    echo "Uso: $0 [ruta_proyecto] [usuario_web]"
    exit 1
fi

echo "Corrigiendo permisos de Laravel en el servidor..."
echo "  Proyecto: $PROJECT_DIR"
echo "  Usuario web: $WEB_USER:$WEB_GROUP"
echo ""

# Propietario de storage y bootstrap/cache
echo "Cambiando propietario de storage..."
sudo chown -R "$WEB_USER:$WEB_GROUP" "$PROJECT_DIR/storage"
echo "Cambiando propietario de bootstrap/cache..."
sudo chown -R "$WEB_USER:$WEB_GROUP" "$PROJECT_DIR/bootstrap/cache"

# Permisos: directorios 775, archivos 664 (Laravel recomienda 775 para storage)
echo "Ajustando permisos (775 directorios, 664 archivos)..."
sudo find "$PROJECT_DIR/storage" - type d -exec chmod 775 {} \;
sudo find "$PROJECT_DIR/storage" - type f -exec chmod 664 {} \;
sudo find "$PROJECT_DIR/bootstrap/cache" - type d -exec chmod 775 {} \;
sudo find "$PROJECT_DIR/bootstrap/cache" - type f -exec chmod 664 {} \;

# Asegurar que storage/framework/views existe y es escribible
mkdir -p "$PROJECT_DIR/storage/framework/views"
sudo chown -R "$WEB_USER:$WEB_GROUP" "$PROJECT_DIR/storage/framework/views"
sudo chmod -R 775 "$PROJECT_DIR/storage/framework/views"

echo ""
echo "Verificando storage/framework/views..."
ls -la "$PROJECT_DIR/storage/framework/views/" | head -5

echo ""
echo "Listo. Si el error persiste, comprueba con qué usuario corre PHP:"
echo "  ps aux | grep php"
echo "  o en tu config de php-fpm/nginx el user/group."
