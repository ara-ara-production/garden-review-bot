# Используем официальный образ Certbot
FROM certbot/certbot:latest

# Копируем папку для вебрута (только если нужно)
COPY ./public /var/www/certbot

# Создаём рабочую директорию
WORKDIR /var/www/certbot

# Скрипт запуска Certbot
ENTRYPOINT ["sh", "-c", "\
if [ ! -f /etc/letsencrypt/live/bot-reviewer.ru/fullchain.pem ]; then \
  certbot certonly --webroot -w /var/www/certbot -d bot-reviewer.ru --agree-tos --email tamanit.dev@yandex.ru --non-interactive; \
fi; \
trap exit TERM; \
while true; do \
  certbot renew --webroot -w /var/www/certbot --quiet; \
  sleep 12h; \
done"]
