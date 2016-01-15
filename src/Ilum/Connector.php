<?php

namespace Ilum;

use PDO;
use Ilum\Drivers\ConnectPDO;
use Ilum\Contracts\IlumInterface;

class Connector extends Operation
{
	protected $connect;
	
	public function __construct($use, $host, $user, $pass, $db, $charset, $co)
	{
		switch($use) {
			case 'pdo':
				$this->usePdo($host, $user, $pass, $db, $charset, $co);
			break;
			case 'mysql';
			 	$this->usePdo($host, $user, $pass, $db, $charset, $co);
			break;
		}
	}
	
	protected function usePdo($host, $user, $pass, $db, $charset, $co = null)
	{
		try {
			$this->connect = new ConnectPDO('mysql:host='.$host.';dbname='.$db, $user, $pass, [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$charset}"
			]);
		}catch(\Exception $e) {
			die($e->getMessage());
		}
	}
}