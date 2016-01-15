<?php

namespace Disting\Http;


trait ContentTrait
{

    protected $headers = array(
        //Informational 1xx
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        //Successful 2xx
        self::__default => 'OK',
        self::CREATED => '201 Created',
        self::ACCEPTED => '202 Accepted',
        self::NONAUTHORITATIVE_INFORMATION => '203 Non-Authoritative Information',
        self::NO_CONTENT => '204 No Content',
        self::RESET_CONTENT => '205 Reset Content',
        self::PARTIAL_CONTENT => '206 Partial Content',
        226 => '226 IM Used',
        //Redirection 3xx
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        //Client Error 4xx
        400 => '400 Bad Request',
        self::UNAUTHORIZED => '401 Unauthorized',
        self::PAYMENT_REQUIRED => '402 Payment Required',
        self::FORBIDDEN => '403 Forbidden',
        self::NOT_FOUND => '404 Not Found',
        self::METHOD_NOT_ALLOWED => '405 Method Not Allowed',
        self::NOT_ACCEPTABLE => '406 Not Acceptable',
        self::PROXY_AUTHENTICATION_REQUIRED => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        self::CONFLICT => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        418 => '418 I\'m a teapot',
        422 => '422 Unprocessable Entity',
        423 => '423 Locked',
        426 => '426 Upgrade Required',
        428 => '428 Precondition Required',
        429 => '429 Too Many Requests',
        431 => '431 Request Header Fields Too Large',
        //Server Error 5xx
        self::INTERNAL_SERVER_ERROR => '500 Internal Server Error',
        self::NOT_IMPLEMENTED => '501 Not Implemented',
        self::BAD_GATEWAY => '502 Bad Gateway',
        self::SERVICE_UNAVAILABLE => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported',
        506 => '506 Variant Also Negotiates',
        510 => '510 Not Extended',
        511 => '511 Network Authentication Required'
    );

    public function isSuccess()
    {
        return $this->status > 200;
    }

    public function isOk()
    {
        return $this->status === 200;
    }

    public function isRedirect()
    {
        return $this->status > 300 && $this->status < 303;
    }

    public function isError()
    {
        return $this->status >= 400 && $this->status <= 500;
    }

    public function isInternal()
    {
        return $this->status >= 500;
    }
}