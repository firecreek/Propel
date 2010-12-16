<?php
/* Milestone Fixture generated on: 2010-12-16 20:12:02 : 1292507762 */
class MilestoneFixture extends CakeTestFixture {
	var $name = 'Milestone';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false),
		'deadline' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'title' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'responsible_person_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'comments_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'completed' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'completed_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'completed_person_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'person_id' => array('type' => 'integer', 'null' => false),
		'updated' => array('type' => 'datetime', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'project_id' => 1,
			'deadline' => '2010-12-16',
			'title' => 'Lorem ipsum dolor sit amet',
			'responsible_person_id' => 1,
			'comments_count' => 1,
			'completed' => 1,
			'completed_date' => '2010-12-16 20:56:02',
			'completed_person_id' => 1,
			'person_id' => 1,
			'updated' => '2010-12-16 20:56:02',
			'created' => '2010-12-16 20:56:02'
		),
	);
}
?>