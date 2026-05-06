# Gunakan base image PHP 8.1 FPM
FROM php:8.1-fpm-alpine

# Setel direktori kerja
WORKDIR /var/www/html

# Instal dependensi yang umum diperlukan Laravel
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libzip-dev \
    jpeg-dev \
    freetype-dev \
    nginx \
    supervisor \
    nodejs \
    npm

# Konfigurasi dan instal ekstensi PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    gd \
    pdo \
    pdo_mysql \
    zip \
    bcmath

# Instal Composer (manajer paket PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin semua file proyek ke dalam image
COPY . .

# Instal dependensi Composer (PHP)
RUN composer install --optimize-autoloader --no-dev

# Instal dependensi NPM (JavaScript) dan build aset
RUN npm install
RUN npm run build

# Setel kepemilikan file agar server web bisa menulis (penting untuk log/cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Ekspos port 9000 untuk FPM
EXPOSE 9000

# Perintah default untuk menjalankan PHP-FPM
CMD ["php-fpm"]