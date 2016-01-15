<?php

class Autoload
{
	
	protected $folder;
	
	public function __construct($folder)
	{
		if(!is_array($folder)){
			throw new \Exception('no es un array');
		}
		$this->add($folder);
	}
	
	protected function add($folder)
	{
		foreach($folder as $load){
			$this->folder[] = $load;
		}
	}
	
	protected function loader($classname)
	{
		$filename = $classname;
		foreach($this->folder as $add){
			$test = $add . str_replace("\\", "/", $filename).'.php';
			if(file_exists($test)){
				include $test;
			}else {
				unset($test);
			}
			
		}
	}
	
	public function run()
	{
		spl_autoload_register(array($this, 'loader'));
	}
}

$load = new Autoload([
	APP_PATH.'/src/',
	APP_PATH.'/app/',
	APP_PATH.'/Database/'
]);
$load->run();