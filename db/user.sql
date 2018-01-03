CREATE TABLE `security_src`.`user` ( 
    `id` INT NOT NULL AUTO_INCREMENT, 
    `username` VARCHAR(50) NOT NULL, 
    `password` VARCHAR(255) NOT NULL, 
    `is_admin` BOOLEAN NOT NULL DEFAULT FALSE, 
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
    PRIMARY KEY (`id`), UNIQUE (`username`)) 
    ENGINE = InnoDB;