FROM bluet/cronicle:latest

# Открываем порт Cronicle
EXPOSE 3012

# Создаем кастомный entrypoint
COPY .deploy/cron/entrypoint.sh /opt/cronicle/entrypoint.sh
RUN chmod +x /opt/cronicle/entrypoint.sh

ENTRYPOINT ["/opt/cronicle/entrypoint.sh"]
