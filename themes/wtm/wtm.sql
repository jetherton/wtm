/**

SQL that was added to the default Ushahidi database for the WTM project

Copyright Etherton Technologies Ltd. 2013
*/

/** John Etherton - 2013-05-02 - The 'Layer legend' feature needs a place to store meta data*/
ALTER TABLE  `layer` ADD  `meta_data` TEXT NULL DEFAULT NULL;

/** John Etherton - 2013-05-09 - Add icons and heirarchy to the layers*/
ALTER TABLE  `layer` ADD  `parent_id` INT( 11 ) NULL DEFAULT '0';
ALTER TABLE  `layer` ADD  `icon` CHAR( 255 ) NULL DEFAULT NULL;

/** Dylan Gillespie 2013-06-20 - Add facebook field to incident_person **/
ALTER TABLE  `incident_person` ADD  `person_facebook` VARCHAR( 200 ) NOT NULL;

/** John Etherton 2013-06-24 - Add a field to store the kind of icon a point uses on the map**/
ALTER TABLE  `geometry` ADD  `geometry_icon` CHAR( 255 ) NULL DEFAULT NULL;

