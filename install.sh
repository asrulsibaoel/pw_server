#!/bin/bash

# Install Packages required for Perfect World server on Debian 8 and Ubuntu 14
# Script courtesy of Wrechid...

#root password for mariadb that will be assigned during 
#instalation used later to create alt user

export sev_dir="/home"

#set -e

export distro=`cat /etc/*-release | grep ^NAME=`
export version=`cat /etc/*-release | grep ^VERSION_ID=`

dpkg --add-architecture i386
apt-get update
apt install -y openjdk-11-jre curl libsvn-java unzip git lib32z1 lib32ncurses6 libgtk2.0-0:i386 libidn11:i386 gstreamer1.0-pulseaudio:i386 gstreamer1.0-plugins-base:i386 gstreamer1.0-plugins-good:i386
# mysql_secure_installation
# if [ -d "/var/www/html/phpmyadmin" ]; then
#     rm -rf /var/www/html/phpmyadmin
# fi
# wget https://www.phpmyadmin.net/downloads/phpMyAdmin-latest-all-languages.zip -P /tmp/
# unzip /tmp/phpMyAdmin-latest-all-languages.zip -d /var/www/html/
# mv /var/www/html/phpMyAdmin* /var/www/html/phpmyadmin
# cp /var/www/html/phpmyadmin/config.sample.inc.php /var/www/html/phpmyadmin/config.inc.php
# sed -i "s/^\$cfg\['blowfish_secret'\] = '';/\$cfg['blowfish_secret'] = 'secret_shitz';/g" /var/www/html/phpmyadmin/config.inc.php
# chmod 660 /var/www/html/phpmyadmin/config.inc.php
# chown -R www-data:www-data /var/www/html/phpmyadmin
# systemctl restart apache2

# mysql -uroot -p$MARIADB_ROOT_PASSWORD -h $MARIADB_HOST -e "CREATE USER '$MARIADB_USER'@localhost IDENTIFIED BY '$MARIADB_PASSWORD';"
# mysql -uroot -p$MARIADB_ROOT_PASSWORD -h $MARIADB_HOST -e "GRANT ALL PRIVILEGES ON *.* TO '$MARIADB_USER'@localhost WITH GRANT OPTION;"
# mysql -uroot -p$MARIADB_ROOT_PASSWORD -h $MARIADB_HOST -e "FLUSH PRIVILEGES;"

# mysql -uroot -p$MARIADB_ROOT_PASSWORD < /root/pwa.sql

[ ! -f /sbin/server ] || rm /sbin/server
ln -s $sev_dir/server /sbin/server
[ ! -f /lib/libpcre.so.0 ] || rm /lib/libpcre.so.0
ln -s $sev_dir/lib/libpcre.so.0 /lib/libpcre.so.0
[ ! -f /lib/libstdc++.so.5 ] || rm /lib/libstdc++.so.5
ln -s $sev_dir/lib/libstdc++.so.5 /lib/libstdc++.so.5
[ ! -f /lib/libtask.so ] || rm /lib/libtask.so
ln -s $sev_dir/lib/libtask.so /lib/libtask.so
[ ! -f /lib/libtask.so.2 ] || rm /lib/libtask.so.2
ln -s $sev_dir/lib/libtask.so.2 /lib/libtask.so.2
[ ! -f /lib/libxml2.so.2 ] || rm /lib/libxml2.so.2
ln -s $sev_dir/lib/libxml2.so.2 /lib/libxml2.so.2

# systemctl enable rc-local
# systemctl start rc-local

echo -e '\r'
echo "All required packages installed..."
echo -e '\r'
echo "Reboot maybe required to update hosts file..."
echo -e '\r'

exit 0