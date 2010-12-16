<?php
/* Post Fixture generated on: 2010-12-16 20:12:51 : 1292507871 */
class PostFixture extends CakeTestFixture {
	var $name = 'Post';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'project_id' => array('type' => 'integer', 'null' => false),
		'person_id' => array('type' => 'integer', 'null' => false),
		'category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'milestone_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'milestone_complete' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'comments_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'private' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'title' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'body' => array('type' => 'text', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'updated' => array('type' => 'datetime', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'project_id' => 1,
			'person_id' => 1,
			'category_id' => 1,
			'milestone_id' => 1,
			'milestone_complete' => 1,
			'comments_count' => 1,
			'private' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'updated' => '2010-12-16 20:57:51',
			'created' => '2010-12-16 20:57:51'
		),
	);
}
?>