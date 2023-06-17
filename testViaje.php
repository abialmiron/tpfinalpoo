<?php

include_once "Viaje.php";
include_once "BaseDatos.php";
include_once "Empresa.php";
include_once "ResponsableV.php";
include_once "Pasajero.php";
include_once "funcionesGenerales.php";

$empresa = new Empresa(1,'prueba','av argentina 2'); 
$responsable = new ResponsableV(1, 2, 'Silvia', 'Ortiz');
$viaje = new Viaje(1, 'paraguay', 32, [] ,$responsable,2292,$empresa->getIdEmpresa());

$respuesta = $viaje->insertar();

if ($respuesta==true) {
    echo "\nOP INSERCION;  El Viaje fue ingresada en la BD";
}else 
echo $viaje->getMensajeOperacion();

?>