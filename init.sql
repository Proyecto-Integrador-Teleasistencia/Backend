ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '1234';
CREATE DATABASE IF NOT EXISTS bdintegrador;
CREATE USER IF NOT EXISTS 'user'@'%' IDENTIFIED WITH mysql_native_password BY '1234';
GRANT ALL PRIVILEGES ON bdintegrador.* TO 'user'@'%';
FLUSH PRIVILEGES;
