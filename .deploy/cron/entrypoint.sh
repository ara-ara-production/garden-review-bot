#!/bin/sh
# Удаляем старый PID
rm -f /opt/cronicle/logs/cronicled.pid

# Стартуем Cronicle
/opt/cronicle/bin/control.sh start

# Держим контейнер живым: создаём пустой файл, если нет, и следим за ним
LOGFILE=/opt/cronicle/logs/cronicled.log
touch $LOGFILE
tail -f $LOGFILE
