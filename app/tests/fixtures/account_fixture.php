<?php
/* Account Fixture generated on: 2010-12-16 20:12:29 : 1292506829 */
class AccountFixture extends CakeTestFixture {
	var $name = 'Account';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'slug' => array('type' => 'string', 'null' => false, 'length' => 60, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'diskspace_used' => array('type' => 'float', 'null' => false, 'length' => '10,2'),
		'project_count' => array('type' => 'integer', 'null' => false),
		'person_id' => array('type' => 'integer', 'null' => false),
		'user_id' => array('type' => 'integer', 'null' => false),
		'updated' => array('type' => 'datetime', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'slug' => 'Lorem ipsum dolor sit amet',
			'diskspace_used' => 1,
			'project_count' => 1,
			'person_id' => 1,
			'user_id' => 1,
			'updated' => '2010-12-16 20:40:29',
			'created' => '2010-12-16 20:40:29'
		),
	);
}
?>