<?php

require_once( 'GigsConfig.php' );

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Gigs',
	
	// application components
	'components'=>array(
		/*'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database

        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=' . GigsConfig::DB_NAME,
            'emulatePrepare' => true,
            'username' => GigsConfig::DB_USERNAME,
            'password' => GigsConfig::DB_PASSWORD,
            'charset' => 'utf8',
        ),
	),
);