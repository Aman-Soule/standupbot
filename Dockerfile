# Utiliser PHP 8.2 avec FPM
FROM php:8.2-fpm

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier les fichiers du projet
COPY . .

# Installer les dépendances Laravel
RUN composer install --no-dev --optimize-autoloader

# Générer les caches Laravel
RUN php artisan config:cache && php artisan route:cache && php artisan view:cache

# Exposer le port
EXPOSE 80

# Commande de démarrage : migrations + serveur
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=80
