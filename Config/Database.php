<?php

return [
		/*
			tipo de conector a la base de datos soportada
			si se desea cambiar a pdo cambiar el valor de defaul
			por 'default' => 'pdo' 
		*/
		
		'run_database' => false,
		
		'default' => 'pdo',
		
		'drivers'	=> [
				'mysql' => [
					'host'		=> 'localhost',
					'username' 	=> 'root',
					'password' 	=> '',
					'database' 	=> '',
					'charset' 	=> 'utf8',
					'collation'	=> 'utf8_unicode_ci'
				],
				
				'pdo' => [
					'host'		=> 'localhost',
					'username' 	=> 'root',
					'password' 	=> '',
					'database' 	=> '',
					'charset' 	=> 'utf8',
					'collation'	=> 'utf8_unicode_ci'
				]
		]
];