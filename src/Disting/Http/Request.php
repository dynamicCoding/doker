<?php

namespace Disting\Http;


use Disting\Contracts\RequestInterface;

class Request implements RequestInterface
{

    protected $ajax;

    protected $server;

    protected $post;

    public function __construct()
    {
        $this->server['referer'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
        $this->server['connection'] = $_SERVER['HTTP_CONNECTION'] === 'keep-alive' ? 'keep-alive' : $_SERVER['HTTP_CONNECTION'];
        $this->server['method'] = $_SERVER['REQUEST_METHOD'] === 'GET' ? 'GET' : $_SERVER['REQUEST_METHOD'];
        $this->server['ip'] = $_SERVER['REMOTE_ADDR'] == '::1' ? '::1' : $_SERVER['REMOTE_ADDR'];
        $this->server['host'] = $_SERVER['SERVER_NAME'] == 'localhost' ? 'localhost' : $_SERVER['SERVER_NAME'];
        $this->server['http'] = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'HTTPS' : 'HTTP';
    }
  
    protected function collectionInfoAjax()
    {
        $this->server['ajax'] = $this->ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ?: 'xmlhttprequest';
    }
    
    public function rawData($action, $data)
    {
    	   	$data = is_array($data) ? $data : array('decode' ,$data);
    		return $this->load($action, $data);
    }

    public function content($content)
    {
        $this->load('content', $content);
    }

    public function redirect($redirect = '')
    {
    	   $this->verifyRedirect($redirect);
    }

    public function status($status)
    {
        $this->load('header', $status);
    }

    public function isAjax()
    {
        return $this->ajax;
    }

    public function input()
    {
        $open = file_get_contents("php://input");
        if(!empty($open)){
            foreach($_POST as $key => $value) {
                $this->post[$key] = $value;
            }
        }
        return $this->post;
    }

    public function allPost()
    {
        return (object)$this->post;
    }

    protected function load($name, $val)
    {
        $response = (new Response)->env($val, $this->server['method'], $this->server['http']);
        switch($name){
            case 'status';
                    $response->responseStatus();
                break;
            case 'content';
                $response->content();
                break;
            case 'redirect';
				$response->redirect();
                break;
            case 'encode':
            	$response->data('encode');
            	 return $response->getJson();
            	 break;
            case 'decode':
            	 $response->data('decode');
            	 return $response->getJson();
            	 break;
            case '';

                break;
        }
    }
    
    protected function verifyRedirect($rdr)
    {
    		if(empty($rdr)) {
    			return $this->load('redirect', $this->server['referer']);
    		}
    		return $this->load('redirect', $rdr);
    }
}