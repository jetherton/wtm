/** Dylan Gillespie -- 2013-14-06 -- Adding private fields table into database **/



CREATE TABLE IF NOT EXISTS `incident_private` 
(
  `incident_title` tinyint(1) NOT NULL DEFAULT '0',  
`incident_description` tinyint(1) NOT NULL DEFAULT '0',  
`incident_date` tinyint(1) NOT NULL DEFAULT '0',  
`incident_time` tinyint(1) NOT NULL DEFAULT '0', 
`incident_firstname` tinyint(1) NOT NULL DEFAULT '1',  
`incident_lastname` tinyint(1) NOT NULL DEFAULT '1',
`incident_email` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

