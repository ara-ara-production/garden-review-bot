FROM nginx:alpine

ADD .deploy/nginx/nginx.prod.conf /etc/nginx/conf.d/default.conf

# Копируем только собранный клиент, папка node_modules больше не нужна
COPY ./public /var/www/public

COPY .deploy/nginx/entrypoint.sh /opt/nginx/entrypoint.sh
RUN chmod +x /opt/nginx/entrypoint.sh


WORKDIR /var/www/public

EXPOSE 80
CMD ["/opt/nginx/entrypoint.sh"]
