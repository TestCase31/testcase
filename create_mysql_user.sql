GRANT ALL PRIVILEGES ON *.* TO 'testcase_user'@'localhost' IDENTIFIED BY PASSWORD '*72EA7A3B37C7CF2067FD7ACD7FF596E05B9A9242' WITH GRANT OPTION;


GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, EXECUTE, CREATE VIEW, SHOW VIEW, CREATE ROUTINE, ALTER ROUTINE, EVENT, TRIGGER ON `ws\_testcase`.* TO 'testcase_user'@'localhost';

