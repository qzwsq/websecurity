FROM ubuntu:latest

RUN mkdir /home/workspace \
&& apt update \
&& DEBIAN_FRONTEND=noninteractive apt install -y apache2 ssh mysql-client php libapache2-mod-php php-mysql

