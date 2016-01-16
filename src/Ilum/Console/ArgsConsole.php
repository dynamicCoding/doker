<?php

namespace Ilum\Console;

use Ilum\Console\Commands;

class ArgsConsole
{
	protected $cmd;
	
	protected $args;
	
	protected $root_std;
	
	protected $root_controller;
	
	protected $root_denied;
	
	protected $root_migration;

    protected $migrate;

    public function __construct()
	{
		$this->root_std = APP_PATH.'/src/Ilum/Console/std/';
		$this->root_controller = APP_PATH.'/app/Http/Controllers/';
		$this->root_denied = APP_PATH.'/app/Denied/';
		$this->root_migration = APP_PATH.'/Database/migration/';
	}

    /**
     * @param $argv get arguments
     */
    public function entrada($argv)
	{
		$this->args = $argv;
		$this->cmd = new Commands;
		if(is_object($argv)) {
			return $this->command($argv->getCmd());
		}
		return $this->explode();
	}

    /**
     * @param $cmd arguments cli all lists commands execute
     */
	protected function command($cmd)
	{
		switch($cmd){
			case 'help':
				$this->lists();
			    break;
			case 'make:controller':
				$this->createController($this->args[2]);
			    break;
			case 'make:model':
			
			    break;
			case 'make:denied':
				$this->createDenied($this->args[2]);
			    break;
            case 'create:db':
                $this->createDb($this->args[2]);
                break;
			case 'make:migration':
				$this->migration($this->args[2]);
			    break;
            case 'migrate:all':
            case 'migrate:truncate':
            case 'migrate:down':
			case 'migrate:up':
				$this->migrate($cmd, $this->args[2]);
			    break;
            case 'drop':
                $this->dropAll($this->args[2]);
                break;
		};
	}

    /**
     * dividir arguments in a string
     */
	protected function explode()
	{
		$argc = $_SERVER['argc'];
		if($argc > 1){
			foreach($this->args as $args){
				$this->command($args);
			}
		}
	}

    /**
     * list commands
     */
	protected function lists()
	{
		$this->cmd->execute('<command>help</command>');

        $this->cmd->despliege([
            'help, --help' => 'muestra la lista de comando disponible',
            'make:controller [name] [option = [plain]]' => 'crea un controlador',
            'make:model' => 'crea un modelo',
            'make:denied [name]'	=> 'crea un metodo de denegacion',
            'make:migration [name]' => 'crea un nuevo archivo de migracion',
            'create:table [option]' => 'crea una tabla',
            'create:db [option]' =>'crea una base de datos',
            'migrate:all' => 'migra todas las tablas que se han creado',
            'migrate:up [option]' => 'migra la tabla creada con los campos creados',
            'migrate:down [option]' => 'elimina la tabla con todo los datos',
            'migrate:truncate [option]' => 'elimina los datos de la tabla',
            'drop [option = [all]]' => 'elimina todas las migraciones creadas'
        ]);
		
		$this->cmd->show();
	}

    /**
     * @param $name name controller
     * create new Controller
     */
    protected function createController($name)
	{
        $file = $this->root_controller.ucwords($name).'Controller.php';

        $this->cmd->execute('<command>make:controller</command>');

        if(isset($this->args[3]) && $this->args[3] == 'plain'){
            $open = file_get_contents($this->root_std.'ControllerPlain.std');
        }else{
            $open = file_get_contents($this->root_std.'Controller.std');
        }

        if(!$this->fileExists($file)){
            $this->create($file, $name.'Controller', $open, FILE_APPEND | LOCK_EX);

            $this->cmd->despliege("\033[32m archivo creado!");
        }

        $this->cmd->show();
	}

    /**
     * @param $name name denied
     * create new Denied
     */
	protected function createDenied($name)
	{
		$file = $this->root_denied.ucwords($name).'Denied.php';
		$open = file_get_contents($this->root_std.'Denied.std');
		$this->cmd->execute('<command>make:denied</command>');
		if(!$this->fileExists($file)) {
			$this->create($file, $name.'Denied', $open, FILE_APPEND | LOCK_EX);
			$this->cmd->despliege("\033[32m archivo creado!");
		}
		$this->cmd->show();
	}

    /**
     * @param $file
     * @return bool
     * verify file exists
     */
	protected function fileExists($file)
	{
		if(file_exists($file)){
			$this->cmd->despliege("\033[31m archivo existente\033[0m");
			return true;
		}
		return false;
	}

    /**
     * @param $name_file
     * @param $name_class
     * @param $write
     * @param null $option
     * create a file
     */
	protected function create($name_file, $name_class, $write, $option = null)
	{
		$write = str_replace('classname', ucwords($name_class), $write);
		file_put_contents($name_file, $write, $option);
	}

    /**
     * @param $name name the migration
     * create new file of migration
     */
	protected function migration($name)
	{
        $name_class = 'Migration_'.date('H_i_s_').ucwords($name);
		$name_migration = $this->root_migration.$name_class;
		$this->cmd->execute('<command>make:migration</command>');
		if(!$this->fileExists($name_migration.'.php')){
			$open = file_get_contents($this->root_std.'Migration.std');
			$write = str_replace(['classname', 'table'], [$name_class, $name], $open);
			if(file_put_contents($name_migration.'.php', $write,  FILE_APPEND | LOCK_EX)){
				$this->cmd->despliege("\033[32m archivo de migracion creado! {$name_migration}");
			}
		}

		$this->cmd->show();
	}

    /**
     * @param $type type command, get execute migration
     * @param $name name migration
     * call this method
     */
	public function migrate($type, $name)
	{
        $m = ucwords(substr(strrchr($type, ':'), 1));

        call_user_func(array($this, 'action'.$m), $name);
	}

    /**
     * @param $name name db
     * create database
     */
    protected function createDb($name)
    {
        $this->migrate->migration("CREATE DATABASE IF NOT EXISTS {$name}");
    }

    /**
     * @param $name
     * ejecuta la migracion
     */
    protected function actionUp($name)
    {
        $class = $this->search($name);
        if(class_exists($class)){
            call_user_func(array(new $class, 'up'));

            $this->cmd->despliege("\033[32m las migracion fueron creadas exitosamente!");
        }else{
            $this->cmd->despliege("\033[31m error al encontrar la clase {$class} \033[0m");
        }
        $this->cmd->show();
    }

    /**
     * @param $name
     * @return string
     * busca el nombre de la migracion
     */
    protected function search($name)
    {
        $scan = scandir($this->root_migration);
        foreach($scan as $file) {
            $search = substr(str_replace('.php', '',$file), 0);
            if(!is_bool($search)) {
                if(stripos($search, $name)){
                    return '\\migration\\'.$search;
                }else{
                    $this->cmd->despliege("\033[31m no se pudo encontrar la migracion {$name} \033[0m");
                }
            }
        }
        $this->cmd->show();
        exit;
    }

    /**
     * @param $name
     * elimina un tabla
     */
    protected function actionDown($name)
    {
        $down_migration = $this->search($name);

        if(class_exists($down_migration)){
            call_user_func(array(new $down_migration, 'down'));

            $this->cmd->despliege("\033[32m migracion eliminada!");
        }
        $this->cmd->show();
    }

    /**
     * @param $name
     * vacia la tabla eliminando todos los datos
     */
    protected function actionTruncate($name)
    {
        $migration = $this->search($name);
        if($name !== 'migration') {
            if (class_exists($migration)) {
                call_user_func(array(new $migration, 'truncate'));

                $this->cmd->despliege("\033[32m la tabla {$name} fue truncada exitosamente!!");
            }
            $this->cmd->show();
        }
    }

    /**
     * @param $all
     */
    protected function actionAll($all)
    {
        if($all == 'table') {
            $scan = scandir($this->root_migration);
            foreach ($scan as $file) {
                $search = substr(str_replace('.php', '', $file), 0);
                if (!is_bool($search)) {
                    $get_class = '\\migration\\' . $search;
                    if (class_exists($get_class)) {
                        call_user_func(array(new $get_class, 'up'));

                        $this->cmd->despliege("\033[32m todas las migraciones fueron creadas exitosamente!");
                    } else {
                        $this->cmd->despliege("\033[31m hubo un error al encontrar la migracion {$get_class} \033[0m");
                    }
                }
            }
            $this->cmd->show();
        }
    }

    /**
     * @param $all
     */
    protected function dropAll($all)
    {
        if($all == 'all') {
            $scan = scandir($this->root_migration);
            foreach ($scan as $file) {
                $search = substr(str_replace('.php', '', $file), 0);
                if (!is_bool($search)) {
                    $get_class = '\\migration\\' . $search;
                    if (class_exists($get_class)) {
                        call_user_func(array(new $get_class, 'down'));

                        $this->cmd->despliege("\033[32m todas las migraciones fueron eliminada exitosamente!");
                    } else {
                        $this->cmd->despliege("\033[31m hubo un error al eliminar la tabla {$get_class} \033[0m");
                    }
                }
            }
            $this->cmd->show();
        }
    }
}