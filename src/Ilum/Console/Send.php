<?php

namespace Ilum\Console;

class Send 
{
	const VERSION = 0.1;
	
	public static function argv()
	{
		$cmd = new Commands;
		
		$argv = $_SERVER['argv'];
		$argc = $_SERVER['argc'];
	
		 $cmd->version('Version: '.self::VERSION);
		 
		if($argc == 2 && in_array($argv[1], array('--help', 'help'))) {
            $cmd->execute('help');
            $cmd->despliege('help');

            return $cmd;
        }
        return $argv;
	}
}