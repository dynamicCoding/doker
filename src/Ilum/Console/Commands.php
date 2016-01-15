<?php

namespace Ilum\Console;

class Commands
{
	protected $cmd;
	
	protected $list;
	
	protected $version = '1.0';
	
	public function version($v)
	{
		$this->version = $v;
	}
	
	public function execute($cmd)
	{
		$this->cmd = $cmd;
	}
	
	public function despliege($list)
	{
		$this->list = $list;
	}
	
	public function getCmd()
	{
		return $this->cmd;
	}
	
	public function getList()
	{
		return $this->list;
	}
	
	public function show()
	{
		$this->messageConsole();
	}
	
	protected function messageConsole()
	{
		echo sprintf("\n %s \n", 'Version: '.$this->version);
		echo sprintf("\n %s \n", $this->cmd);
		
		if(is_array($this->list)) {
			$k = array_keys($this->list);
			$v = array_values($this->list);
			return array_map(array($this, 'map'), $k, $v);
		}
		echo sprintf("\n %s",$this->list);
	}
	
	protected function map($k, $v)
	{
		$mask = "\n %-30.40s %5s\n";
		echo sprintf($mask, $k, $v);
	}
}