/** Dylan Gillespie -- 2013-14-06 -- Adding private fields table into database **/



CREATE TABLE IF NOT EXISTS `incident_private` (
`id` INT NOT NULL,
`incident_id` INT NOT NULL,
`incident_title` BOOLEAN NOT NULL DEFAULT '0',
  
`incident_description` BOOLEAN NOT NULL DEFAULT '0',
  
`incident_date` tinyint(1) NOT NULL DEFAULT '0',
`incident_location` BOOLEAN NOT NULL DEFAULT '0',
  
`incident_firstname` BOOLEAN NOT NULL DEFAULT '1',
  
`incident_lastname` BOOLEAN NOT NULL DEFAULT '1',
  
`incident_email` BOOLEAN NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE  `incident_private` ADD PRIMARY KEY (  `id` );
ALTER TABLE  `incident_private` ADD UNIQUE (
`incident_id`
);