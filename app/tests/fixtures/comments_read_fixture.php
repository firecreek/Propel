<?php
/* CommentsRead Fixture generated on: 2010-12-16 20:12:10 : 1292507470 */
class CommentsReadFixture extends CakeTestFixture {
	var $name = 'CommentsRead';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'person_id' => array('type' => 'integer', 'null' => false),
		'model' => array('type' => 'string', 'null' => false, 'length' => 39, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'model_id' => array('type' => 'integer', 'null' => false),
		'created' => array('type' => 'datetime', 'null' => false),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'person_id' => 1,
			'model' => 'Lorem ipsum dolor sit amet',
			'model_id' => 1,
			'created' => '2010-12-16 20:51:10'
		),
	);
}
?>