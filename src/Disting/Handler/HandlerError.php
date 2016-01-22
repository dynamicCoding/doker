<?php

namespace Disting\Handler;

use Disting\Handler\HandlerException as DokerException;
use Disting\Handler\ErrorTemplate;
use Disting\Environment;

class HandlerError
{
	const NOTICE = 1;
	
	const INFO = 2;
	
	const ERROR = 3;
	
	const WARNING = 4;
	
	const ALERT = 5;
	
	const EMERGENCY = 6;
	
	const DEBUG = 7;
	
	protected $environment;
	
	protected $logDate;
	
	protected $logPath;
	
	protected $logFile;
	
	protected $error_notice;
	
	public function __construct(Environment $env)
	{
		$this->environment = $env;
	}
	
	public function errorNotice($n)
	{
		$this->error_notice = $n;
	}
	
	public function logMessageWithDate($date)
	{
		$this->logDate = $date;
	}
	
	public function logMessagePath($path)
	{
		$this->logPath = $path;
	}
	
	public function inicializeError($level, $msg_error, $file, $line)
	{
		if(!(error_reporting() & $level)){
			if($this->error_notice !== true) {
				return;
			}
		}
		
		$this->msg = $msg_error;
		$this->file = $file;
		$this->line = $line;
		
		(new ErrorTemplate)->error(new DokerException(
			$msg_error, $level, 0, $file, $line)
		);
		
		$this->save();
		
		exit;
	}
	
	public function logFile($file)
	{
		$this->logFile = $file;
	}
	
	public function getLogPath()
	{
		return $this->logPath;
	}
	
	public function getLogFile()
	{
		return $this->logFile;
	}
	
	public function getLogDate()
	{
		return $this->logDate;
	}
	
	public function isDebug()
	{
		return $this->environment->getDebug() == true;
	}
	
	public function environment()
	{
		return $this->environment;
	}
	
	public function save()
	{
		if(!empty($this->msg)) {
			$date = $this->getLogDate() ? 'date: '.date('d-m-Y h:i:s') : '';
			
			error_log($date . '  msg: '.$this->msg. "\r\n file: ".$this->file."\r\n line: {$this->line} \r\n", 3, $this->getLogPath().$this->getLogFile());
			
			return;
		}
	}
}