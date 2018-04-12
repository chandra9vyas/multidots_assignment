<?php
return [
	'basePath' => dirname(__DIR__),	
	'name' => 'Yii2Latest',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => ['gii'],
    'components' => [
    	'db' => require(dirname(__FILE__).'/db.php'),   
        
        'authManager' => [
				'class' => 'yii\rbac\DbManager',
				'itemTable' => 'auth_item',
				'itemChildTable' => 'auth_item_child',
				'assignmentTable' => 'auth_assignment',
				'ruleTable' => 'auth_rule',
				'defaultRoles' => ['guest']
		],
      	'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        
        'urlManager' => [          
			'enablePrettyUrl' => true,
			'showScriptName' => false, 
			'rules' => [                
			],
		],

    ],
];
