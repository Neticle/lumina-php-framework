DROP DATABASE IF EXISTS `lumina_examples_oauth_provider`;

CREATE DATABASE `lumina_examples_oauth_provider` DEFAULT COLLATE `utf8_general_ci` DEFAULT CHARSET `utf8`;

USE `lumina_examples_oauth_provider`;

DROP TABLE IF EXISTS `oauth_access_token`;
DROP TABLE IF EXISTS `oauth_authorization_code`;
DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (

	`id` INT UNSIGNED AUTO_INCREMENT NOT NULL,
	`username` VARCHAR(32) NOT NULL,
	`password` CHAR(60) NOT NULL,
	`active` TINYINT UNSIGNED NOT NULL DEFAULT 0,

	CONSTRAINT `user_pk_id`
		PRIMARY KEY (`id`),

	CONSTRAINT `user_uq_username`
		UNIQUE (`username`)

);

CREATE TABLE `oauth_authorization_code` (

	`code` VARCHAR(255) NOT NULL,
	`id_user` INT UNSIGNED NOT NULL,
	`id_client` VARCHAR(64) NOT NULL,
	`expiration_date` TIMESTAMP NOT NULL,
	`status` TINYINT UNSIGNED NOT NULL,

	CONSTRAINT `oauth_authorization_code_pk_code` 
		PRIMARY KEY (`code`),

	CONSTRAINT `oauth_authorization_code_fk_id_user` 
		FOREIGN KEY (`id_user`) REFERENCES `user` (`id`)
		ON DELETE CASCADE ON UPDATE CASCADE

);

CREATE TABLE `oauth_access_token` (

	`token` VARCHAR(255) NOT NULL,
	`code` VARCHAR(255) NOT NULL,
	`id_user` INT UNSIGNED NOT NULL,
	`id_client` VARCHAR(64) NOT NULL,
	`expiration_date` TIMESTAMP NOT NULL,
	`context_type` TINYINT UNSIGNED NOT NULL,
	`status` TINYINT UNSIGNED NOT NULL,
	`refresh_token` VARCHAR(255) NULL,

	CONSTRAINT `oauth_access_token_pk_token` 
		PRIMARY KEY (`token`),

	CONSTRAINT `oauth_access_token_fk_code` 
		FOREIGN KEY (`code`) REFERENCES `oauth_authorization_code` (`code`)
		ON DELETE CASCADE ON UPDATE CASCADE,

	CONSTRAINT `oauth_access_token_fk_id_user` 
		FOREIGN KEY (`id_user`) REFERENCES `user` (`id`)
		ON DELETE CASCADE ON UPDATE CASCADE

);
