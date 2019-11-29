# /bin/bash
set -e

mkdir -p /root/CA
cd /root/CA
cp /home/workspace/CA/*.cnf /root/CA
touch index.txt
echo '01' > serial
touch index.txt.attr

openssl genrsa -passout pass:123456 -des3 -out myCA.key 2048

openssl req -x509 -new -nodes -key myCA.key -sha256 -days 1825 -out myCA.crt -passin pass:123456 -subj "/C=CN/ST=London/L=London/O=webSecurity/OU=websecurity/CN=localhost"

cp myCA.crt /home/workspace/

openssl genrsa -out websecurity.key 2048

openssl req -new -key websecurity.key -out websecurity.csr -subj "/C=CN/ST=London/L=London/O=webSecurity/OU=websecurity/CN=localhost"

openssl ca -batch -passin pass:123456 -config ca.cnf -out websecurity.crt -extfile extensions.cnf -in websecurity.csr

cd -

a2enmod ssl

service apache2 start
while true
do
    sleep 1
    echo 'running'
done
