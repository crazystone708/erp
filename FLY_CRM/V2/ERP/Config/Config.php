<?php
/**
 * @CopyRight  (C)2006-2011 07fly Development team Inc.
**/
//用户配置
 return array (

	'URLMode'   => 0,			
	'ActionDir' => 'hiddenDir/',
	'htmlExt'  => '.html',
	'ReWrite'  => true,
	'Router'  => '',
	'Debug'     => true,  
	'Session'   => true,
	'pageSize'  =>10,
	'xml'=>array(
		'path'=>EXTEND.'xml',
		'root'=>'niaomuniao',
	),	
	'DB'=>array(
		'Persistent'=>false,
		'DBtype'    => 'Mysql',
		'DBcharSet' => 'utf8',
		'DBhost'    => '39.96.55.38',
		'DBport'    => '3306',
		'DBuser'    => 'root',
		'DBpsw'     => 'Dengxiao@1219',
		'DBname'    => 'FLYCRM'
	),
	'setSmarty'=>array(
		'template_dir'    => VIEW.'template',
		'compile_dir'     => _mkdir(CACHE. 'c_templates'),
		'left_delimiter'  => '#{',
		'right_delimiter' => '}#',
	),	
); 
?>