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

/** John Etherton 2013-06-27 - Added a field to store if the label of an element should be seen on the map**/
ALTER TABLE  `geometry` ADD  `geometry_showlabel` BOOLEAN NOT NULL DEFAULT FALSE;

/** John Etherton 2013-07-07 - Added fields for lots of text and polygon features**/
ALTER TABLE  `geometry` ADD  `geometry_fontsize` INT(11) NOT NULL;
ALTER TABLE  `geometry` ADD  `geometry_fontcolor` CHAR(8) NOT NULL;
ALTER TABLE  `geometry` ADD  `geometry_labeloutlinewidth` INT(11) NOT NULL;
ALTER TABLE  `geometry` ADD  `geometry_labeloutlinecolor` CHAR(8) NOT NULL;

ALTER TABLE  `geometry` ADD  `geometry_strokeColor` CHAR( 8 ) NOT NULL ,
ADD  `geometry_fillOpacity` FLOAT NOT NULL ,
ADD  `geometry_strokeOpacity` FLOAT NOT NULL ,
ADD  `geometry_strokeDashstyle` CHAR( 20 ) NOT NULL;

/** Dylan Gillespie 2013-07-09 - Added zindex field **/
ALTER TABLE  `geometry` ADD  `geometry_zindex` INT(11) NOT NULL DEFAULT  '0';

/** Etherton 2013-07-14 - Added a field to store the icon of a layer*/
ALTER TABLE  `layer` ADD  `layer_icon_thumb` CHAR( 255 ) NULL DEFAULT NULL;

/** Etherton 2013-07-31 - Adding a column to keep track of layer order */
ALTER TABLE  `layer` ADD  `layer_order` INT NOT NULL DEFAULT  '0';