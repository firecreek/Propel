<?php
class SettingData {

	public $table = 'settings';

	public $records = array(
		array(
			'id' => '1',
			'key' => 'Email.delivery',
			'value' => 'mail',
			'title' => 'Method for email sending',
			'description' => 'Supported methods are mail and smtp',
			'input_type' => 'text',
			'weight' => '1',
			'params' => ''
		),
		array(
			'id' => '2',
			'key' => 'Email.host',
			'value' => 'localhost',
			'title' => 'Email host',
			'description' => '',
			'input_type' => 'text',
			'weight' => '2',
			'params' => ''
		),
		array(
			'id' => '3',
			'key' => 'Email.port',
			'value' => '25',
			'title' => 'Email port',
			'description' => 'Default 25',
			'input_type' => 'text',
			'weight' => '3',
			'params' => ''
		),
		array(
			'id' => '4',
			'key' => 'Email.timeout',
			'value' => '30',
			'title' => 'Email timeout',
			'description' => '',
			'input_type' => 'text',
			'weight' => '4',
			'params' => ''
		),
		array(
			'id' => '5',
			'key' => 'Email.username',
			'value' => '',
			'title' => 'Email username',
			'description' => '',
			'input_type' => 'text',
			'weight' => '5',
			'params' => ''
		),
		array(
			'id' => '6',
			'key' => 'Email.password',
			'value' => '',
			'title' => 'Email password',
			'description' => '',
			'input_type' => 'text',
			'weight' => '6',
			'params' => ''
		),
		array(
			'id' => '7',
			'key' => 'Email.client',
			'value' => '',
			'title' => 'Email.client',
			'description' => '',
			'input_type' => 'text',
			'weight' => '7',
			'params' => ''
		),
		array(
			'id' => '8',
			'key' => 'Email.replyName',
			'value' => 'Your name',
			'title' => 'Email reply name',
			'description' => '',
			'input_type' => 'text',
			'weight' => '8',
			'params' => ''
		),
		array(
			'id' => '9',
			'key' => 'Email.replyEmail',
			'value' => 'site@site.com',
			'title' => 'Email reply address',
			'description' => '',
			'input_type' => 'text',
			'weight' => '9',
			'params' => ''
		),
	);

}
?>