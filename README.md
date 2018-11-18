# Tatar-War


سكربت حرب التتار لموقع اكس تتار xtatar.com لعبت المتصفح الشبيهه بترافيان

# System Requirement 

1- Need php >= 7.1 

2- Activate .htaccess files from httpd.conf file (AllowOverride All)


# How to install game ?
1- you need to make new database with user then change (setting/db.php) with you data


2- go to phpMyAdmin and in your database in sql tap past m.sql content in it


3- now open your game admin http://yourSite/AdminCP/login

username: ahmed2game@gmail.com

password: 123456

4- you can edit admin data in (المشرفين) 

5- if you are work in Cpanel panel you can add server auto from game admin in (السيرفرات)

but you need change cpanel url in (settings/db.php)

--Or if you on {localhost} or any other places you need to add database manual

Important database name must have prefix aaaa_m for master and for servers database must be same prefix aaaa_1 or 2 this server number 

you can change aaaa to any prefix you want

after add server from (السيرفرات) then change server number in the top of menu go to (اعدادات سريعه ثم اعدادات اللعبه وقم بالتعديل علي السيرفر )

then go to (السيرفرات ثم اعداة سيرفر ثم تثبيت سيرفر جديد )

# Send mail settings 

Go to file (system/library/functions.php) in line 429 431 432 change server and user name and password with your data