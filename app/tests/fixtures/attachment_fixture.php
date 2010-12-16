<?php
/* Attachment Fixture generated on: 2010-12-16 20:12:56 : 1292507156 */
class AttachmentFixture extends CakeTestFixture {
	var $name = 'Attachment';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'byte_size' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 20),
		'project_id' => array('type' => 'integer', 'null' => false),
		'category_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'private' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'model' => array('type' => 'string', 'null' => false, 'length' => 30, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'model_id' => array('type' => 'integer', 'null' => false),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'version' => array('type' => 'integer', 'null' => false, 'length' => 4),
		'current' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'person_id' => array('type' => 'integer', 'null' => false),
		'updated' => array('type' => 'datetime', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'byte_size' => 1,
			'project_id' => 1,
			'category_id' => 1,
			'private' => 1,
			'model' => 'Lorem ipsum dolor sit amet',
			'model_id' => 1,
			'parent_id' => 1,
			'version' => 1,
			'current' => 1,
			'person_id' => 1,
			'updated' => '2010-12-16 20:45:56',
			'created' => '2010-12-16 20:45:56'
		),
	);
}
?>