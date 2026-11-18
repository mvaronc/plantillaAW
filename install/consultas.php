<?php   //Al menos tenemos que tener la tabla users con los siguientes campos mínimos 
        //podemos cambiar los roles modificando el enum
        $consultas[]="CREATE TABLE if not exists `users` (
                `id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `login` VARCHAR(50) NOT NULL ,
                `name` VARCHAR(100) NOT NULL ,
                `surname1` VARCHAR(100) NOT NULL ,
                `surname2` VARCHAR(100) NULL ,
                `email` VARCHAR(100) NOT NULL ,
                `password` VARCHAR(512) NOT NULL ,
                `rol` ENUM('admin','registrado') NOT NULL default('registrado') ,
                `enabled` BOOLEAN NOT NULL default('1') ,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                 PRIMARY KEY (`id`),
                 UNIQUE (`login`),
                 UNIQUE (`email`))
                 ENGINE = InnoDB; ";
          //Aqui el resto de consultas de creación de tablas que se necesiten para la aplicación dentro de la variable array $consultas[]

