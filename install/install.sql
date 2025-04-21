CREATE TABLE IF NOT EXISTS `tbltraccar_devices` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `vehicle_id` VARCHAR(10) NOT NULL,
    `traccar_device_id` VARCHAR(255) NOT NULL,
    `last_sync` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tblvehiculos` 
ADD COLUMN `traccar_policy` VARCHAR(20) DEFAULT '3_facturas',
ADD COLUMN `traccar_blocked` TINYINT(1) DEFAULT 0;