/**

SQL that was added to the default Ushahidi database for the WTM project

Copyright Etherton Technologies Ltd. 2013
*/

/** John Etherton - 2013-05-02 - The 'Layer legend' feature needs a place to store meta data*/
ALTER TABLE  `layer` ADD  `meta_data` TEXT NULL DEFAULT NULL;

/** John Etherton - 2013-05-09 - Add icons and heirarchy to the layers*/
ALTER TABLE  `layer` ADD  `parent_id` INT( 11 ) NULL DEFAULT '0';
ALTER TABLE  `layer` ADD  `icon` CHAR( 255 ) NULL DEFAULT NULL;
