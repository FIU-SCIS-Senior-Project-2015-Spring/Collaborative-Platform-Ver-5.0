<?php

// uncomment the following to define a path alias Yii::setPathOfAlias('local','path/to/local-folder');
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
Yii::setPathOfAlias('editable', dirname(__FILE__).'/../extensions/x-editable');
Yii::setPathOfAlias('booster', dirname(__FILE__).'/../extensions/booster');
Yii::setPathOfAlias('fiuCustom', dirname(__FILE__).'/../controllers/custom');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Collaborative Platform',
    'theme'=>'bootstrap',
	'modules'=>array(
        'gii'=>array(
            'generatorPaths'=>array(
                'bootstrap.gii',
            ),
		),
	),
	// preloading 'log' component
	'preload'=>array('log', 'editable', ),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
                'application.components.analytics.*',
		'editable.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'gii',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		'mailbox'=>array(
				'userClass'=>'User',
				'userIdColumn'=>'id',
				'usernameColumn'=>'username',
				),		
	),

	// application components
	'components'=>array(
			//X-editable config
			'editable' => array(
					'class'     => 'editable.EditableConfig',
					'form'      => 'bootstrap',        //form style: 'bootstrap', 'jqueryui', 'plain'
					'mode'      => 'inline',            //mode: 'popup' or 'inline'
					'defaults'  => array(              //default settings for all editable elements
							'emptytext' => 'Click to edit'
					)
			),
			
			
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		'email'=>array(
				'class'=>'application.extensions.email.Email',
				'delivery'=>'php', //Will use the php mailing function.
			),
		/*		
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'connectionString' => 'mysql:host=127.0.0.1;dbname=coplat',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '9Qst32+',
			'charset' => 'utf8',
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		'bootstrap'=>array(
			'class'=>'bootstrap.components.Bootstrap'
		),
		'booster'=>array(
			'class'=>'booster.components.Booster'
		),
		'multicomplete'=>array(
				'class'=>'multicomplete.MultiComplete.php')		
		),
		

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);