# /bin/bash
set -e

a2enmod ssl
service apache2 start
while true
do
    sleep 1
    echo 'running'
done
