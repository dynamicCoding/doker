<?php

namespace Disting\Http;


class Response
{
    use ContentTrait;

    const __default = self::OK;
    const SWITCHING_PROTOCOLS = 101;
    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const NONAUTHORITATIVE_INFORMATION = 203;
    const NO_CONTENT = 204;
    const RESET_CONTENT = 205;
    const PARTIAL_CONTENT = 206;
    const MULTIPLE_CHOICES = 300;
    const MOVED_PERMANENTLY = 301;
    const MOVED_TEMPORARILY = 302;
    const SEE_OTHER = 303;
    const NOT_MODIFIED = 304;
    const USE_PROXY = 305;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const PAYMENT_REQUIRED = 402;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const NOT_ACCEPTABLE = 406;
    const PROXY_AUTHENTICATION_REQUIRED = 407;
    const REQUEST_TIMEOUT = 408;
    const CONFLICT = 408;
    const GONE = 410;
    const LENGTH_REQUIRED = 411;
    const PRECONDITION_FAILED = 412;
    const REQUEST_ENTITY_TOO_LARGE = 413;
    const REQUESTURI_TOO_LARGE = 414;
    const UNSUPPORTED_MEDIA_TYPE = 415;
    const REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    const EXPECTATION_FAILED = 417;
    const IM_A_TEAPOT = 418;
    const INTERNAL_SERVER_ERROR = 500;
    const NOT_IMPLEMENTED = 501;
    const BAD_GATEWAY = 502;
    const SERVICE_UNAVAILABLE = 503;
    const GATEWAY_TIMEOUT = 504;
    const HTTP_VERSION_NOT_SUPPORTED = 505;

    protected $status;

    protected $method_http;

    protected $content;

    protected $protocol_http;
    
    protected $json;
    
    protected $data;

    protected $decode;

    public function env($cnt, $method, $protocol)
    {
        $this->method_http = $method;
        $this->protocol_http = $protocol;
        if(is_int($cnt)){
            $this->status($cnt);
        }elseif(is_string($cnt)){
            $this->blockContent($cnt);
        }elseif(is_array($cnt)){
            if(isset($cnt[0]) && $cnt[0] == 'decode') {
                list($decode, $json) = $cnt;
                $this->decode = $json;
                return;
            }else{
                $this->data = $cnt;
            }
        }

        return $this;
    }

    protected function status($status)
    {
        if(isset($this->header[$status])){
            $this->status = $this->headers[$status];
        }
    }
    
    public function data($name)
    {
    		if($name === 'encode') {
    			$this->json = json_encode($this->data, JSON_UNESCAPED_UNICODE);
    		}elseif($name === 'decode') {
    			$this->json = json_decode($this->decode);
    		}
    }

    public function responseStatus()
    {
    	   if(!headers_sent()) {
        		header($this->protocol_http.'/1.1 '. $this->status);
        }
    }

    protected function blockContent($content)
    {
        $this->content = $content;
    }

    public function content()
    {
    	   if(!headers_sent()) {
        	header('Content-Type: '. $this->content);
        }
    }
    
    public function redirect()
    {
    		$redirect = $this->content;
    		$this->status(301);
    		if(!headers_sent()) {
    			$this->responseStatus();
    			header('Location: '.$redirect);
    		}
    }
    
    public function getJson()
    {
    		return $this->json;
    }
    
    public function getBlockContent()
    {
    		return $this->content;
    }
}