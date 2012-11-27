ALTER TABLE `product_option_value` ADD `model` VARCHAR(64) NOT NULL;
ALTER TABLE `product_option_value` ADD `sku` VARCHAR(64) NOT NULL;
alter table `address` modify  `firstname` varchar(128) NOT NULL DEFAULT '';
alter table `address` modify  `lastname` varchar(128) NOT NULL DEFAULT '';