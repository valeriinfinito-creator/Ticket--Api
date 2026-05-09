FROM php:8.4-fpm

WORKDIR /var/www

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    unzip git libpng-dev libonig-dev \
    libxml2-dev zip curl gnupg2 \
    apt-transport-https ca-certificates

# Instalar drivers ODBC para SQL Server
RUN curl -sSL https://packages.microsoft.com/keys/microsoft.asc \
        | gpg --dearmor -o /usr/share/keyrings/microsoft-prod.gpg \
    && echo "deb [arch=amd64 signed-by=/usr/share/keyrings/microsoft-prod.gpg] https://packages.microsoft.com/debian/12/prod bookworm main" \
        > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 unixodbc-dev

# Instalar extensiones PHP
RUN docker-php-ext-install pdo mbstring exif pcntl bcmath gd \
    && pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-scripts --verbose
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]