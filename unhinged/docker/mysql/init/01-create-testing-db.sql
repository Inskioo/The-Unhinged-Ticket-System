-- /docker/mysql/init/01-create-testing-db.sql
SET @OLD_SQL_MODE=@@SQL_MODE;
SET SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE DATABASE IF NOT EXISTS testing CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user if it doesn't exist
CREATE USER IF NOT EXISTS 'sail'@'%' IDENTIFIED BY 'password';

-- Grant privileges
GRANT ALL PRIVILEGES ON testing.* TO 'sail'@'%';
GRANT ALL PRIVILEGES ON unhinged.* TO 'sail'@'%';

FLUSH PRIVILEGES;

SET SQL_MODE=@OLD_SQL_MODE;