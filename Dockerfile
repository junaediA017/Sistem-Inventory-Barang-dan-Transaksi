FROM php:8.2-fpm

# Argument untuk menerima UID & GID dari host
ARG USER_ID=1000
ARG GROUP_ID=1000

# Update dan install library sistem
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpng-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd intl mysqli zip

# Buat user baru (xxx) agar tidak menggunakan root
RUN groupadd -g ${GROUP_ID} xxx && \
    useradd -u ${USER_ID} -g xxx -m -s /bin/bash xxx

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set user aktif ke xxx
USER xxx

WORKDIR /usr/src/myapp
