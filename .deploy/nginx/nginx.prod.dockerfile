FROM nginx:alpine

ADD .deploy/nginx/nginx.prod.conf /etc/nginx/conf.d/default.conf

# Копируем только собранный клиент, папка node_modules больше не нужна
COPY ./public /var/www/public

WORKDIR /var/www/public

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
