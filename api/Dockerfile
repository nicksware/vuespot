FROM archlinux/base:latest
RUN pacman -Sy --noconfirm reflector && \
    reflector --latest 5 --sort rate --save /etc/pacman.d/mirrorlist 
# PHP
RUN pacman -Suy --noconfirm php php-sqlite composer git unzip  && \ 
    sed -i -- 's/;extension=sockets/extension=sockets/g' /etc/php/php.ini &&\
    sed -i -- 's/;extension=gmp/extension=gmp/g' /etc/php/php.ini &&\
    sed -i -- 's/;extension=sqlite3/extension=sqlite3/g' /etc/php/php.ini &&\
    sed -i -- 's/;extension=pdo_sqlite/extension=pdo_sqlite/g' /etc/php/php.ini
# extention
RUN pacman -Syu --noconfirm sudo base-devel && \
    useradd -m -s /bin/bash aur && \
    echo "aur ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers && \
    visudo -cqs -f /etc/sudoers
USER aur
WORKDIR /home/aur
RUN curl https://aur.archlinux.org/cgit/aur.git/snapshot/php-gnupg.tar.gz | tar xz && \
    cd php-gnupg && \
    makepkg -sri --noconfirm
USER root
RUN sed -i '$ d' /etc/sudoers && \
    visudo -cqs -f /etc/sudoers && \
    pacman -Rsuncd --noconfirm sudo $(pacman -Qtdq) && \
    yes | pacman -Scc && \
    userdel -r aur && \
    rm -fr /home/aur
# API
WORKDIR /srv/http
COPY . /srv/http
RUN composer install
VOLUME /srv/http/assets
CMD /usr/bin/php -S 0.0.0.0:80 /srv/http/index.php
ENTRYPOINT /usr/bin/php -S 0.0.0.0:80 /srv/http/index.php