if [ ! -d "/home/wwwroot" ]; then
    mkdir /home/wwwroot;
fi;
if [ ! -d "/home/wwwbackup" ]; then
    mkdir /home/wwwbackup;
fi;
zip -r /home/wwwbackup/acggeek.zip /home/wwwroot/acggeek/*


if [ ! -d "/home/wwwsrc" ]; then
    mkdir /home/wwwsrc;
fi;

if [ ! -d "/home/wwwsrc/acggeek" ]; then
    cd /home/wwwsrc;
    git clone https://github.com/Ltre/acggeek.git;
else
    cd /home/wwwsrc/acggeek;
    git pull;
fi

if [ ! -d "/home/wwwroot/acggeek" ]; then
    mkdir /home/wwwroot/acggeek;
fi

if [ ! -d "/home/wwwroot/acggeek/core" ]; then
    mkdir /home/wwwroot/acggeek/core;
fi

if [ ! -d "/home/wwwroot/acggeek/core/data" ]; then
    mkdir /home/wwwroot/acggeek/core/data;
fi


mv /home/wwwroot/acggeek /home/wwwroot/acggeek.trash;
cp /home/wwwsrc/acggeek -r /home/wwwroot/acggeek;
rm /home/wwwroot/acggeek/.git -rf
rm /home/wwwroot/acggeek/core/data -rf
cp -r /home/wwwroot/acggeek.trash/core/data /home/wwwroot/acggeek/core/
chmod -R 767 /home/wwwroot/acggeek/core/data;
chmod +x /home/wwwroot/acggeek/core/setting/gitpull.sh;
rm -f -r /home/wwwroot/acggeek.trash;

cd /home/wwwroot

service nginx restart
service php-fpm reload
