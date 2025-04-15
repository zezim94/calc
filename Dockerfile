FROM php:8.1-apache

# Ativa o mod_rewrite
RUN a2enmod rewrite

# Copia o código PHP para o container
COPY interface_php/ /var/www/html/

# Corrige permissões
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
