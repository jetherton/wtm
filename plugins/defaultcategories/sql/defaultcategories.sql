/** Dylan Gillespie -- 2013-07-06 -- Adding default option to category database **/

ALTER TABLE  `category` ADD  `category_default` BOOLEAN NOT NULL DEFAULT FALSE;