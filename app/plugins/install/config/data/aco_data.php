<?php
class AcoData {

	public $table = 'acos';

	public $records = array(
		array(
			'id' => '1',
			'parent_id' => '',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'propel',
			'lft' => '1',
			'rght' => '246'
		),
		array(
			'id' => '2',
			'parent_id' => '1',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'models',
			'lft' => '2',
			'rght' => '57'
		),
		array(
			'id' => '3',
			'parent_id' => '1',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'controllers',
			'lft' => '58',
			'rght' => '245'
		),
		array(
			'id' => '4',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Accounts',
			'lft' => '59',
			'rght' => '62'
		),
		array(
			'id' => '5',
			'parent_id' => '4',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_index',
			'lft' => '60',
			'rght' => '61'
		),
		array(
			'id' => '6',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Categories',
			'lft' => '63',
			'rght' => '70'
		),
		array(
			'id' => '7',
			'parent_id' => '6',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'add',
			'lft' => '64',
			'rght' => '65'
		),
		array(
			'id' => '8',
			'parent_id' => '6',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'edit',
			'lft' => '66',
			'rght' => '67'
		),
		array(
			'id' => '9',
			'parent_id' => '6',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'delete',
			'lft' => '68',
			'rght' => '69'
		),
		array(
			'id' => '10',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Comments',
			'lft' => '71',
			'rght' => '80'
		),
		array(
			'id' => '11',
			'parent_id' => '10',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'index',
			'lft' => '72',
			'rght' => '73'
		),
		array(
			'id' => '12',
			'parent_id' => '10',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'delete',
			'lft' => '74',
			'rght' => '75'
		),
		array(
			'id' => '13',
			'parent_id' => '10',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'subscribe',
			'lft' => '76',
			'rght' => '77'
		),
		array(
			'id' => '14',
			'parent_id' => '10',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'unsubscribe',
			'lft' => '78',
			'rght' => '79'
		),
		array(
			'id' => '15',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Companies',
			'lft' => '81',
			'rght' => '98'
		),
		array(
			'id' => '16',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_index',
			'lft' => '82',
			'rght' => '83'
		),
		array(
			'id' => '17',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_add',
			'lft' => '84',
			'rght' => '85'
		),
		array(
			'id' => '18',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_edit',
			'lft' => '86',
			'rght' => '87'
		),
		array(
			'id' => '19',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_delete',
			'lft' => '88',
			'rght' => '89'
		),
		array(
			'id' => '20',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_index',
			'lft' => '90',
			'rght' => '91'
		),
		array(
			'id' => '21',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_permissions',
			'lft' => '92',
			'rght' => '93'
		),
		array(
			'id' => '22',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_add',
			'lft' => '94',
			'rght' => '95'
		),
		array(
			'id' => '23',
			'parent_id' => '15',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_delete',
			'lft' => '96',
			'rght' => '97'
		),
		array(
			'id' => '24',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Dashboard',
			'lft' => '99',
			'rght' => '102'
		),
		array(
			'id' => '25',
			'parent_id' => '24',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '100',
			'rght' => '101'
		),
		array(
			'id' => '26',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Milestones',
			'lft' => '103',
			'rght' => '120'
		),
		array(
			'id' => '27',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_index',
			'lft' => '104',
			'rght' => '105'
		),
		array(
			'id' => '28',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_index',
			'lft' => '106',
			'rght' => '107'
		),
		array(
			'id' => '29',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_add',
			'lft' => '108',
			'rght' => '109'
		),
		array(
			'id' => '30',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_add_multiple',
			'lft' => '110',
			'rght' => '111'
		),
		array(
			'id' => '31',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_edit',
			'lft' => '112',
			'rght' => '113'
		),
		array(
			'id' => '32',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_update',
			'lft' => '114',
			'rght' => '115'
		),
		array(
			'id' => '33',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_move_project',
			'lft' => '116',
			'rght' => '117'
		),
		array(
			'id' => '34',
			'parent_id' => '26',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_delete',
			'lft' => '118',
			'rght' => '119'
		),
		array(
			'id' => '35',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Pages',
			'lft' => '121',
			'rght' => '124'
		),
		array(
			'id' => '36',
			'parent_id' => '35',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'display',
			'lft' => '122',
			'rght' => '123'
		),
		array(
			'id' => '37',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'People',
			'lft' => '125',
			'rght' => '138'
		),
		array(
			'id' => '38',
			'parent_id' => '37',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_add',
			'lft' => '126',
			'rght' => '127'
		),
		array(
			'id' => '39',
			'parent_id' => '37',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_edit',
			'lft' => '128',
			'rght' => '129'
		),
		array(
			'id' => '40',
			'parent_id' => '37',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_delete',
			'lft' => '130',
			'rght' => '131'
		),
		array(
			'id' => '41',
			'parent_id' => '37',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_invite_resend',
			'lft' => '132',
			'rght' => '133'
		),
		array(
			'id' => '42',
			'parent_id' => '37',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_add',
			'lft' => '134',
			'rght' => '135'
		),
		array(
			'id' => '43',
			'parent_id' => '37',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_edit',
			'lft' => '136',
			'rght' => '137'
		),
		array(
			'id' => '44',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Permissions',
			'lft' => '139',
			'rght' => '146'
		),
		array(
			'id' => '45',
			'parent_id' => '44',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '140',
			'rght' => '141'
		),
		array(
			'id' => '46',
			'parent_id' => '44',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_actions',
			'lft' => '142',
			'rght' => '143'
		),
		array(
			'id' => '47',
			'parent_id' => '44',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_actions_update',
			'lft' => '144',
			'rght' => '145'
		),
		array(
			'id' => '48',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Posts',
			'lft' => '147',
			'rght' => '158'
		),
		array(
			'id' => '49',
			'parent_id' => '48',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_index',
			'lft' => '148',
			'rght' => '149'
		),
		array(
			'id' => '50',
			'parent_id' => '48',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_add',
			'lft' => '150',
			'rght' => '151'
		),
		array(
			'id' => '51',
			'parent_id' => '48',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_edit',
			'lft' => '152',
			'rght' => '153'
		),
		array(
			'id' => '52',
			'parent_id' => '48',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_move_project',
			'lft' => '154',
			'rght' => '155'
		),
		array(
			'id' => '53',
			'parent_id' => '48',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_delete',
			'lft' => '156',
			'rght' => '157'
		),
		array(
			'id' => '54',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Projects',
			'lft' => '159',
			'rght' => '168'
		),
		array(
			'id' => '55',
			'parent_id' => '54',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_add',
			'lft' => '160',
			'rght' => '161'
		),
		array(
			'id' => '56',
			'parent_id' => '54',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_start',
			'lft' => '162',
			'rght' => '163'
		),
		array(
			'id' => '57',
			'parent_id' => '54',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_index',
			'lft' => '164',
			'rght' => '165'
		),
		array(
			'id' => '58',
			'parent_id' => '54',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_edit',
			'lft' => '166',
			'rght' => '167'
		),
		array(
			'id' => '59',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Search',
			'lft' => '169',
			'rght' => '178'
		),
		array(
			'id' => '60',
			'parent_id' => '59',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_index',
			'lft' => '170',
			'rght' => '171'
		),
		array(
			'id' => '61',
			'parent_id' => '59',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_index',
			'lft' => '172',
			'rght' => '173'
		),
		array(
			'id' => '62',
			'parent_id' => '59',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_forget',
			'lft' => '174',
			'rght' => '175'
		),
		array(
			'id' => '63',
			'parent_id' => '59',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_forget',
			'lft' => '176',
			'rght' => '177'
		),
		array(
			'id' => '64',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Settings',
			'lft' => '179',
			'rght' => '192'
		),
		array(
			'id' => '65',
			'parent_id' => '64',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_index',
			'lft' => '180',
			'rght' => '181'
		),
		array(
			'id' => '66',
			'parent_id' => '64',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'admin_view',
			'lft' => '182',
			'rght' => '183'
		),
		array(
			'id' => '67',
			'parent_id' => '64',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_index',
			'lft' => '184',
			'rght' => '185'
		),
		array(
			'id' => '68',
			'parent_id' => '64',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_logo',
			'lft' => '186',
			'rght' => '187'
		),
		array(
			'id' => '69',
			'parent_id' => '64',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_appearance',
			'lft' => '188',
			'rght' => '189'
		),
		array(
			'id' => '70',
			'parent_id' => '64',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_categories',
			'lft' => '190',
			'rght' => '191'
		),
		array(
			'id' => '71',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Todos',
			'lft' => '193',
			'rght' => '210'
		),
		array(
			'id' => '72',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_index',
			'lft' => '194',
			'rght' => '195'
		),
		array(
			'id' => '73',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_index',
			'lft' => '196',
			'rght' => '197'
		),
		array(
			'id' => '74',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_view',
			'lft' => '198',
			'rght' => '199'
		),
		array(
			'id' => '75',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_add',
			'lft' => '200',
			'rght' => '201'
		),
		array(
			'id' => '76',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_edit',
			'lft' => '202',
			'rght' => '203'
		),
		array(
			'id' => '77',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_delete',
			'lft' => '204',
			'rght' => '205'
		),
		array(
			'id' => '78',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_move_project',
			'lft' => '206',
			'rght' => '207'
		),
		array(
			'id' => '79',
			'parent_id' => '71',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_update_positions',
			'lft' => '208',
			'rght' => '209'
		),
		array(
			'id' => '80',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'TodosItems',
			'lft' => '211',
			'rght' => '222'
		),
		array(
			'id' => '81',
			'parent_id' => '80',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_add',
			'lft' => '212',
			'rght' => '213'
		),
		array(
			'id' => '82',
			'parent_id' => '80',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_edit',
			'lft' => '214',
			'rght' => '215'
		),
		array(
			'id' => '83',
			'parent_id' => '80',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_delete',
			'lft' => '216',
			'rght' => '217'
		),
		array(
			'id' => '84',
			'parent_id' => '80',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_update',
			'lft' => '218',
			'rght' => '219'
		),
		array(
			'id' => '85',
			'parent_id' => '80',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'project_update_positions',
			'lft' => '220',
			'rght' => '221'
		),
		array(
			'id' => '86',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'Users',
			'lft' => '223',
			'rght' => '238'
		),
		array(
			'id' => '87',
			'parent_id' => '86',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'register',
			'lft' => '224',
			'rght' => '225'
		),
		array(
			'id' => '88',
			'parent_id' => '86',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'invitation',
			'lft' => '226',
			'rght' => '227'
		),
		array(
			'id' => '89',
			'parent_id' => '86',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'login',
			'lft' => '228',
			'rght' => '229'
		),
		array(
			'id' => '90',
			'parent_id' => '86',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'logout',
			'lft' => '230',
			'rght' => '231'
		),
		array(
			'id' => '91',
			'parent_id' => '86',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'forgotten',
			'lft' => '232',
			'rght' => '233'
		),
		array(
			'id' => '92',
			'parent_id' => '86',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'reset',
			'lft' => '234',
			'rght' => '235'
		),
		array(
			'id' => '93',
			'parent_id' => '86',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'account_edit',
			'lft' => '236',
			'rght' => '237'
		),
		array(
			'id' => '94',
			'parent_id' => '3',
			'model' => '',
			'foreign_key' => '',
			'alias' => 'ToolbarAccess',
			'lft' => '239',
			'rght' => '244'
		),
	);

}
?>