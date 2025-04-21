DROP TABLE IF EXISTS `tbltraccar_devices`;
ALTER TABLE `tblvehiculos` 
DROP COLUMN `traccar_policy`,
DROP COLUMN `traccar_blocked`;