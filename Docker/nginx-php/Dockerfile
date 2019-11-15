FROM ubuntu:18.04

RUN apt-get update && apt-get upgrade -y

RUN apt-get update && apt-get upgrade -y

ENV TZ=Europe/Lisbon
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN apt-get update -y

RUN apt-get install nginx -y

RUN apt-get install git composer -y
RUN apt-get install libpq-dev -y
RUN apt-get install vim -y
RUN DEBIAN_FRONTEND=noninteractive apt-get install php7.2 -y \
        && apt-get install php7.2-mbstring -y \
        && apt-get install php7.2-xml -y \
        && apt-get install php7.2-pgsql -y \
        && apt-get install php7.2-bcmath -y \
        && apt-get install php7.2-soap -y \
        && apt-get install php7.2-curl -y \
        && apt-get install php7.2-zip -y \
        && apt-get install php7.2-fpm -y \
        && apt-get install php7.2-xdebug -y \
        && apt-get clean
RUN apt-get install postgresql-client -y
RUN apt-get install curl -y

#COPY local files
COPY . /var/www
RUN chown -R www-data:www-data /var/www

# WORKDIR /usr/share/nginx/html
WORKDIR /var/www

CMD nginx -g "daemon off;"

