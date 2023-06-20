<?php

include_once "Viaje.php";
include_once "BaseDatos.php";
include_once "Empresa.php";
include_once "ResponsableV.php";
include_once "Pasajero.php";
include_once "funcionesGenerales.php";

function menu_principal(){

    $mensaje = '---------- MENU PRINCIPAL ----------' . "\n";
    $mensaje .="1) Ingresar un nuevo viaje \n";
    $mensaje .= "2) Listar todos los viajes \n";
    $mensaje .= "3) Modificar algún viaje \n";
    $mensaje .= "4) Eliminar algún viaje \n";
    $mensaje .= "5) Agregar a un pasajero nuevo \n";
    $mensaje .= "6) Modificar algún pasajero \n";
    $mensaje .= "7) Eliminar algún pasajero \n";
    $mensaje .= "8) Modificar algún responsable de viajes \n";
    $mensaje .= "9) Eliminar algún responsable de viajes \n";

    $ingresoUsuario = verificaIngresoNumerico($mensaje, 1,9);

    return $ingresoUsuario;
}

function ingresa_empresa(){
    $mensaje = "Desea seleccionar una empresa ya cargada o quiere ingresar una nueva?";
    $mensaje .= "\n 1) Ya cargada";
    $mensaje .= "\n 2) Ingresar nueva \n";

    $ingresoUsuario = verificaIngresoNumerico($mensaje, 1,2);

    switch($ingresoUsuario){
        case 1:
            $colEmpresas = Empresa::listar("");
            $arregloID = array();
            echo " ******* EMPRESAS YA CARGADAS ********* \n";

            for($i = 0;$i < count($colEmpresas);$i++){
                $arregloID[] = $colEmpresas[$i]->getIdEmpresa();
                echo $colEmpresas[$i];
            }
            do{
            $idEmpresa = verificaIngreso("Ingrese el ID de la empresa con la que quiere trabajar: \n ");
            $verificaID = array_search($idEmpresa,$arregloID) === false;
            }while($verificaID);

            $empresaSeleccionada = new Empresa();
            $empresaSeleccionada->buscar($idEmpresa);

            break;
        case 2:
            $nombre = verificaIngreso("Ingrese el nombre de la empresa nueva: \n");
            $direccion = verificaIngreso("Ingrese la dirección:\n ");

            $empresaSeleccionada = new Empresa();
            $empresaSeleccionada->setNombre($nombre);
            $empresaSeleccionada->setDireccion($direccion);
            $empresaSeleccionada->insertar();
            break;
    } 
    return $empresaSeleccionada;


}

function cargaViaje($objEmpresa){
    $des = verificaIngreso("Ingrese el destino:\n");
    $cantMaxPasajeros = verificaIngreso("Ingrese la cantidad máxima de pasajeros:\n");
    $costo  = verificaIngreso("Ingrese el costo del viaje: \n");
    $objResponsable = cargaResponsable();

    $objViaje = new Viaje();
    $objViaje->cargar(0,$des,$cantMaxPasajeros,[],$objResponsable,$costo,$objEmpresa);
    $objViaje->insertar();

    $colPasajeros = cargaPasajerosInicial($objViaje);
    $objViaje->setPasajeros($colPasajeros);
    
}

function cargaResponsable(){
    $numLic = verificaIngreso("Ingrese el número de licencia del responsable: \n");
    $nombreR = verificaIngreso("Ingrese el nombre del responsable: \n");
    $apellidoR = verificaIngreso("Ingrese el apellido del responsable: \n");
    
    $objResponsable = new ResponsableV();
    $objResponsable->cargar(0,$numLic,$nombreR,$apellidoR);
    $objResponsable->insertar();

    return $objResponsable;
}

/** Esta función se encarga de realizar la carga inicial de un viaje.
 */
function cargaPasajerosInicial($objViaje){
    $colPasajeros = array();
    $contador = 0;
    do{
        $contador += 1;
        $dni = verificaDNI($colPasajeros);
        $nombre = verificaIngreso("Ingrese el nombre del nuevo pasajero: \n");
        $apellido = verificaIngreso("Ingrese el apellido: \n");
        $telefono = verificaIngreso("Ingrese el telefono: \n");

        $objPasajero = new Pasajero();
        $objPasajero->cargar($dni,$nombre,$apellido,$objViaje->getCodViaje(), $telefono);
        $objPasajero->insertar();

        array_push($colPasajeros,$objPasajero);
        $objViaje->venderPasaje();
        $respuesta = pregunta();
    }while($respuesta && $contador < $objViaje->getCantMaximaPasajeros());
    return $colPasajeros;
}

function listaViajes($objEmpresa){
    $colViajes = Viaje::listar("idempresa = ".$objEmpresa->getIdEmpresa());
    for($i = 0;$i < count($colViajes);$i++){
        echo $colViajes[$i];
    }
}

function modificarViaje($objEmpresa){
    $colViajes = Viaje::listar("idempresa = ".$objEmpresa->getIdEmpresa());
    for($i = 0;$i < count($colViajes);$i++){
        $arrCodViajes[] = $colViajes[$i]->getCodViaje();
        echo $colViajes[$i];
    }

    do{
        $codViaje = verificaIngreso("Ingrese el codViaje del viaje con el que quiere trabajar: \n ");
        $verificaID = array_search($arrCodViajes,$codViaje) === false;
    }while($verificaID);

    $objViaje = new Viaje();
    $objViaje->buscar($codViaje);

    $mensaje = "¿Qué desea modificar del viaje? \n";
    $mensaje .= "1) Destino \n";
    $mensaje .= "2) Cantidad Máxima de pasajeros \n";
    $mensaje .= "3) Asignar otro responsable al viaje \n";
    $mensaje .= "4) Cambiar la empresa \n";
    $mensaje .= "5) Costo \n";

    $modificacion = verificaIngresoNumerico($mensaje, 1,5);

}


/** MENU PRINCIPAL */

$objEmpresa = ingresa_empresa();

$ingresoUsuario = menu_principal();

switch($ingresoUsuario){
    case 1: 
        echo "Bienvenido a la carga de un nuevo viaje \n";
        cargaViaje($objEmpresa);
    break;
    case 2: 
        echo "Se listan todos los viajes creados: \n";
        listaViajes($objEmpresa);
    break;
    case 3:
        echo "Se muestran los viajes almacenados: \n";
        listaViajes($objEmpresa);
        modificarViaje($objEmpresa);
    break;
}

?>