#!/bin/sh
set -e

echo "Preparando el entorno de producción para Laravel..."

# Esperar a que la base de datos esté lista (opcional, PostgreSQL lo maneja con reconexiones)
# Optimizar caché de Laravel
echo "Optimizando la configuración y rutas de Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones
echo "Ejecutando las migraciones de la base de datos..."
php artisan migrate --force

# Detectar de forma inteligente si la base de datos está vacía para sembrar datos semilla (seeders)
echo "Verificando si se requiere cargar datos semilla..."
HAS_PRODUCTS=$(php -r "
    require 'vendor/autoload.php';
    \$app = require_once 'bootstrap/app.php';
    \$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
    \$kernel->bootstrap();
    try {
        echo \App\Models\Producto::count() > 0 ? 'yes' : 'no';
    } catch (\Exception \$e) {
        echo 'no';
    }
")

if [ "$HAS_PRODUCTS" = "no" ]; then
    echo "Base de datos vacía detectada. Sembrando datos demo de productos y categorías..."
    php artisan db:seed --force
else
    echo "La base de datos ya contiene información. Se omiten los seeders."
fi

# Iniciar Supervisor (que a su vez arranca Nginx y PHP-FPM)
echo "Iniciando Nginx y PHP-FPM a través de Supervisor..."
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
