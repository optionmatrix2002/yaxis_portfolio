ALTER TABLE `tbl_gp_audits` ADD `frequency_value` TINYINT(4) NOT NULL AFTER `end_date`, ADD `frequency_duration` TINYINT(4) NULL AFTER `frequency_value`;