FROM php:8.2-cli

# Dépendances système
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_pgsql zip

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copier les fichiers
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Compiler les assets frontend
RUN npm install && npm run build

# Permissions storage
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 80

# Au démarrage : clear les caches buildés, reconstruire avec les vraies env vars, puis servir
CMD php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=80
```

---

## ✅ Variables d'environnement à ajouter sur Render

