# test-task
1. в vhosts.conf - виртуал хост
<VirtualHost *:80>
ServerName server_name
ServerAlias www.server_name
DocumentRoot /path/to/folder/web
ErrorLog /path/to/folder/error.log
CustomLog /path/to/folder/requests.log combined
</VirtualHost>
2. php conposer.phar update - для твига
3. структура бд в db_structure.sql (без create database) 
4. в Task\settings\settings.php настройки для коннекта к базе
5. chown -R на папку cache для апач юзера
6. .htaccess лежит в /web

вроде все
