# Stage 1: Compilar assets de frontend (Tailwind y Alpine.js)
FROM node:20-alpine AS asset-builder
WORKDIR /app
COPY . .
RUN npm ci && npm run build

# Stage 2: Imagen final de producción
FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones de base de datos
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    nginx \
    supervisor

# Limpiar cache de APT
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP necesarias para Laravel y PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos del proyecto
COPY . /var/www/html

# Copiar los assets compilados en el Stage 1
COPY --from=asset-builder /app/public/build /var/www/html/public/build

# Instalar dependencias de Composer para producción
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Configurar Nginx y Supervisor
COPY nginx.conf /etc/nginx/sites-available/default
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Dar permisos de ejecución al script de arranque
RUN chmod +x /var/www/html/entrypoint.sh

# Ajustar permisos para las carpetas de almacenamiento y caché de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/var/www/html/entrypoint.sh"]
