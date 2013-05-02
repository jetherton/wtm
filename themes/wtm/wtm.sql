/**

SQL that was added to the default Ushahidi database for the WTM project

Copyright Etherton Technologies Ltd. 2013
*/

/** John Etherton - 2013-05-02 - The 'Layer legend' feature needs a place to store meta data*/
ALTER TABLE  `layer` ADD  `meta_data` TEXT NULL DEFAULT NULL;