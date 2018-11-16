DROP USER 'wc'@'localhost';
CREATE USER 'wc'@'localhost' IDENTIFIED BY 'webcrawler';
GRANT ALL PRIVILEGES ON *.* TO 'wc'@'localhost'
    WITH GRANT OPTION;