# Usamos a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instala as dependências necessárias para o SQLite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Habilita o mod_rewrite do Apache (necessário para o .htaccess funcionar)
RUN a2enmod rewrite

# Copia os arquivos do projeto para o diretório padrão do Apache
COPY . /var/www/html/

# Define as permissões corretas para a pasta de dados (PHP precisa escrever no banco)
RUN chown -R www-data:www-data /var/www/html/data && \
    chmod -R 775 /var/www/html/data

# Expõe a porta 80
EXPOSE 80
