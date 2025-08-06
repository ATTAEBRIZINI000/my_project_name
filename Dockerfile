FROM php:8.4-fpm

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    zip \
    curl \
  && docker-php-ext-install intl pdo pdo_pgsql zip mbstring

# Install latest Composer (change version if you want)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create a non-root user and switch to it
RUN useradd -m appuser
USER appuser
WORKDIR /var/www/html

# Copy project files and run composer install
COPY --chown=appuser:appuser . .

# Set environment variable to allow composer plugins in root-like environment
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Expose port 9000 and start PHP-FPM
EXPOSE 8000
CMD ["php-fpm"]
