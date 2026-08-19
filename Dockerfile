FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones de PHP necesarias para Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Limpiar caché de apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones de PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Copiar el código del proyecto
COPY . /var/www/html

# Instalar dependencias de Composer sin Dev
RUN composer install --no-dev --optimize-autoloader

# Configurar permisos correctos para Laravel (vital para storage y cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Cambiar la raíz de Apache para que apunte directamente a la carpeta 'public' de Laravel
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Habilitar mod_rewrite de Apache para las rutas amigables de Laravel
RUN a2enmod rewrite

# Exponer el puerto 80 que es el que lee Render
EXPOSE 80

# Comando de inicio para arrancar Apache en primer plano
CMD ["apache2-foreground"]