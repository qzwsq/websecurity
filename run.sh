# /bin/bash
set -e

mkdir -p /root/CA
cd /root/CA

openssl genrsa -passout pass:123456 -des3 -out myCA.key 2048

openssl req -x509 -new -nodes -key myCA.key -sha256 -days 1825 -out myCA.pem -passin pass:123456 -subj "/C=CN/ST=London/L=London/O=webSecurity/OU=websecurity/CN=example.com"

cp myCA.pem /home/workspace/

openssl genrsa -out websecurity.key 2048

openssl req -new -key websecurity.key -out websecurity.csr -subj "/C=CN/ST=London/L=London/O=webSecurity/OU=websecurity/CN=example.com"

openssl x509 -req -passin pass:123456 -in websecurity.csr -CA myCA.pem -CAkey myCA.key -CAcreateserial -out websecurity.crt -days 1825 -sha256 

cd -
a2enmod ssl
service apache2 start
while true
do
    sleep 1
    echo 'running'
done
