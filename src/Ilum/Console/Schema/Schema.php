<?php

namespace Ilum\Console\Schema;

use Closure;
use Ilum\Console\Schema\Type;

Final class Schema 
{
	public static function up($table, Closure $closure)
	{
		call_user_func($closure, new Type('createTable', $table));
	}
	
	public static function down($table)
	{
		new Type('down', $table);
	}
	
	public static function truncate($table)
	{
		new Type('truncate', $table);
	}
	
	public static function back($table, Closure $closure)
	{
		
	}
}