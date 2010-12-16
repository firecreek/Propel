<?php
/* TodosItem Fixture generated on: 2010-12-16 20:12:00 : 1292507940 */
class TodosItemFixture extends CakeTestFixture {
	var $name = 'TodosItem';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false),
		'todo_id' => array('type' => 'integer', 'null' => false),
		'time_total' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5),
		'responsible_person_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'due_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'position' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'comment_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
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
			'todo_id' => 1,
			'time_total' => 1,
			'responsible_person_id' => 1,
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'due_date' => '2010-12-16',
			'position' => 1,
			'comment_count' => 1,
			'completed' => 1,
			'completed_date' => '2010-12-16 20:59:00',
			'completed_person_id' => 1,
			'person_id' => 1,
			'updated' => '2010-12-16 20:59:00',
			'created' => '2010-12-16 20:59:00'
		),
	);
}
?>