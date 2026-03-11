# Usamos a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Define o fuso horário (Timezone)
ENV TZ=America/Sao_Paulo

# Instala as dependências, configura o fuso horário e limpa cache
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    tzdata \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

# Configura o Timezone no PHP.ini (via comando direto)
RUN echo "date.timezone = America/Sao_Paulo" > /usr/local/etc/php/conf.d/timezone.ini

# Habilita o mod_rewrite do Apache (necessário para o .htaccess funcionar)
RUN a2enmod rewrite

# Copia os arquivos do projeto para o diretório padrão do Apache
COPY . /var/www/html/

# Define as permissões corretas para a pasta de dados (PHP precisa escrever no banco)
RUN chown -R www-data:www-data /var/www/html/data && \
    chmod -R 775 /var/www/html/data

# Expõe a porta 80
EXPOSE 80
