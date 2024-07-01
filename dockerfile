# 기본 베이스를 우분투 20.04로 설정
FROM ubuntu:20.04

# APT 저장소를 업데이트 및 업그레이드
RUN apt-get update -y && apt-get upgrade -y

# 한국 시간을 사용하기 위해 우분투의 타임존관련 패키지 설치 및 타임존을 Asia/Seoul로 변경
RUN apt-get install -y tzdata
RUN ln -sf /usr/share/zoneinfo/Asia/Seoul /etc/localtime

# 기본 우분투 패키지를 설치
RUN apt-get install -y gcc make telnet whois vim git gettext cron mariadb-client iputils-ping net-tools wget lsb-release apt-transport-https ca-certificates software-properties-common

# PHP 8.1 저장소 추가 및 APT 저장소 업데이트
RUN apt-get update -y && apt-get install -y software-properties-common && add-apt-repository ppa:ondrej/php -y

# APT 저장소 업데이트
RUN apt-get update -y

# Apache, PHP 8.1 및 PHP의 확장 패키지를 설치
RUN apt-get install -y apache2 apache2-utils
RUN apt-get install -y php8.1 php8.1-dev libapache2-mod-php8.1 composer
RUN apt-get install -y php8.1-mysql php8.1-mbstring php8.1-curl php8.1-gd php8.1-imagick php8.1-xmlrpc php8.1-zip php8.1-soap php8.1-memcache php8.1-redis php-pear

# PHP Mime관련 라이브러리를 설치
RUN pear install MIME_Type

# 아파치 SSL을 설정하기 위해 ssl enable 및 기본 인증서를 설치
RUN mkdir /etc/apache2/ssl
RUN openssl genrsa -out /etc/apache2/ssl/server.key 2048
RUN openssl req -new -days 365 -key /etc/apache2/ssl/server.key -out /etc/apache2/ssl/server.csr -subj "/C=KR/ST=Daejeon/L=Daejeon/O=Docker/OU=IT Department/CN=localhost"
RUN openssl x509 -req -days 365 -in /etc/apache2/ssl/server.csr -signkey /etc/apache2/ssl/server.key -out /etc/apache2/ssl/server.crt
RUN sed 's|/etc/ssl/certs/ssl-cert-snakeoil.pem|/etc/apache2/ssl/server.crt|g' /etc/apache2/sites-available/default-ssl.conf > /etc/apache2/sites-available/default-ssl.conf.tmp
RUN sed 's|/etc/ssl/private/ssl-cert-snakeoil.key|/etc/apache2/ssl/server.key|g' /etc/apache2/sites-available/default-ssl.conf.tmp > /etc/apache2/sites-enabled/000-default-ssl.conf
RUN rm /etc/apache2/sites-available/default-ssl.conf.tmp -f

# 아파치에서 사용할 모듈을 enable
RUN a2enmod ssl
RUN a2enmod cache
RUN a2enmod cache_disk
RUN a2enmod expires
RUN a2enmod headers
RUN a2enmod rewrite

# Work 디렉토리, 볼륨, 포트 및 Entrypoint 스크립트를 설정
WORKDIR /var/www/html
VOLUME ["/var/www/html"]
EXPOSE 80
EXPOSE 443
CMD ["apache2ctl", "-D", "FOREGROUND"]



### 빌드
### docker build -t apache2-php8-ubuntu .
### 컨테이너실행
### docker run --name myci4-container -d -p 80:80 -p 443:443 -v ~/my-ci4/:/var/www/html apache2-php8-ubuntu

