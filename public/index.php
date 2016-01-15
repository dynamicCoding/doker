<?php


use Disting\Handler\HandlerError;
use Disting\Environment;
use Disting\Doker;
use Disting\Bootstrap;

require 'start.php';

/*
	tipo de entorno que desea ejecutar
	development, production;
*/
try {

    if(version_compare(phpversion(), "5.4.4", "<")) {
        throw new \Exception("la version de php debe de ser mayor o igual a 5.4");
    }

    $handler = new HandlerError(new Environment('development', true));

    /*
        mostrar error si la clase y el metodo no se ah encontrado
        si se activa se controller uri no mostrara pagina de error
    */
    $handler->environment()->showErrorNotFoundClassAndMethod(false);

    /*
        mostrar error 404 si no se encuentra la clase
    */
    $handler->environment()->controllerUrl();

    /*
        control de modificacion de parametros por get
    */
    $handler->environment()->alertModifyParamsUrl();

    /*
        configuracion de  errores de la aplicacion
    */
    $handler->errorNotice(true);
    $handler->logMessageWithDate(true);
    $handler->logMessagePath('app/log/');
    $handler->logFile('error_application.log');
    /*
        @class Doker inicia la aplicacion parametros de class Bootstrap y del HandlerError
        @class Bootstrap controlador default y method default
    */
    $app = new Doker(new Bootstrap("Index", "home"), $handler);

} catch(\Exception $e) {
    die($e->getMessage());
}