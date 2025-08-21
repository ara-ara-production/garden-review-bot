#!/bin/sh
# wait-for-cert.sh

CERT=/etc/letsencrypt/live/bot-reviewer.ru/fullchain.pem

echo "Waiting for SSL certificate..."
while [ ! -f "$CERT" ]; do
  sleep 2
done

echo "Certificate found, starting Nginx..."
exec nginx -g "daemon off;"
